<?php
/** @noinspection PhpIncludeInspection */
require MODX_CORE_PATH . 'model/modx/processors/security/user/update.class.php';

class officeProfileUserUpdateProcessor extends modUserUpdateProcessor
{
    public $classKey = 'modUser';
    public $languageTopics = array('core:default', 'core:user');
    public $permission = '';
    public $beforeSaveEvent = 'OnBeforeUserFormSave';
    public $afterSaveEvent = 'OnUserFormSave';
    protected $_new_email;
    protected $_new_phone;
    protected $_current_email;
    protected $_current_phone;
    protected $_current_photo;
    /** @var Office $office */
    public $office;


    /**
     * @return bool|string
     */
    public function initialize()
    {
        $this->setProperty('id', $this->modx->user->id);
        $this->office = $this->modx->getService('office', 'Office', MODX_CORE_PATH . 'components/office/model/office/');

        return parent::initialize();
    }


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $this->_current_email = $this->object->Profile->get('email');
        $this->_current_phone = $this->object->Profile->get('mobilephone');
        $this->_current_photo = $this->object->Profile->get('photo');

        $fields = $this->getProperty('requiredFields', '');
        if (!empty($fields) && is_array($fields)) {
            foreach ($fields as $field) {
                // Extended fields
                if (preg_match_all('#(.*?)(\[.*?\]+)#', $field, $matches)) {
                    $data = $this->getProperties();
                    if (isset($data[$matches[1][0]])) {
                        $tmp = $data[$matches[1][0]];
                        $keys = array($matches[1][0]);
                        foreach ($matches[2] as $key) {
                            $key = trim($key, '[]');
                            $keys[] = $key;
                            if (isset($tmp[$key])) {
                                $tmp = $tmp[$key];
                            }
                        }
                        if (empty($tmp)) {
                            if (!isset($this->modx->error->errors[$matches[1][0]])) {
                                $this->modx->error->errors[$matches[1][0]] = array();
                            }
                            $error = &$this->modx->error->errors[$matches[1][0]];
                            foreach ($keys as $k) {
                                if (!isset($error[$k])) {
                                    $error[$k] = array();
                                }
                                $error = &$error[$k];
                            }
                            $error = $this->modx->lexicon('field_required');
                            unset($error);
                        }
                    }
                } else {
                    $tmp = $this->getProperty($field, null);
                    if ($field == 'email') {
                        if (!preg_match('#^[^@а-яА-Я]+@[^@а-яА-Я]+(?<!\.)\.[^\.а-яА-Я]{2,}$#m', $tmp)) {
                            $this->addFieldError('email', $this->modx->lexicon('office_profile_err_email'));
                        } else {
                            $count = $this->modx->getCount('modUserProfile', array(
                                'email' => $tmp,
                                'internalKey:!=' => $this->object->id,
                            ));
                            if ($count) {
                                $this->addFieldError('email', $this->modx->lexicon('office_profile_err_email_exists'));
                            }
                        }
                    } elseif ($field == 'mobilephone') {
                        if (!$tmp = $this->office->checkPhone($tmp)) {
                            $this->addFieldError('mobilephone', $this->modx->lexicon('office_profile_err_phone'));
                        } else {
                            $count = $this->modx->getCount('modUserProfile', array(
                                'mobilephone' => $tmp,
                                'internalKey:!=' => $this->object->id,
                            ));
                            if ($count) {
                                $this->addFieldError('mobilephone',
                                    $this->modx->lexicon('office_profile_err_phone_exists')
                                );
                            }
                        }
                        $this->setProperty($field, $tmp);
                    } elseif (empty($tmp)) {
                        $this->addFieldError($field, $this->modx->lexicon('field_required'));
                    }
                }
            }
        }
        // Fields required by parent processor
        if (!$this->getProperty('username')) {
            $this->setProperty('username', $this->object->get('username'));
        }
        if (!$this->_new_email = $this->getProperty('email')) {
            $this->setProperty('email', $this->_current_email);
        }
        if (!$this->_new_phone = $this->getProperty('mobilephone')) {
            $this->setProperty('mobilephone', $this->_current_phone);
        }
        // Add existing extended fields
        if ($extended = $this->getProperty('extended')) {
            if ($existing = $this->object->Profile->get('extended')) {
                $extended = array_replace_recursive($existing, $extended);
            }
            $this->setProperty('extended', $extended);
        }
        // Handle new password
        if ($this->getProperty('specifiedpassword') || $this->getProperty('confirmpassword')) {
            $this->setProperty('passwordnotifymethod', 's');
            $this->setProperty('passwordgenmethod', 'spec');
            $this->setProperty('newpassword', '');
        }
        // Allow only uploaded images
        if ($photo = $this->getProperty('photo')) {
            if (strpos($photo, '://') !== false) {
                $this->unsetProperty('photo');
            }
        }

        return parent::beforeSet();
    }


    /**
     * @return bool
     */
    public function beforeSave()
    {
        $before = parent::beforeSave();

        if ($before) {
            $this->handlePhoto();
        }

        return $before;
    }


    /**
     * Upload user photo
     *
     * @return bool
     */
    public function handlePhoto()
    {
        $params = json_decode($this->getProperty('avatarParams'), true);
        if (!is_array($params)) {
            $params = array();
        }

        $path = trim($this->getProperty('avatarPath', 'images/users/'), '/') . '/';
        $file = strtolower(md5($this->object->id . time()) . '.' . $params['f']);

        $url = MODX_ASSETS_URL . $path . $file;
        $dst = MODX_ASSETS_PATH . $path . $file;

        // Check image dir
        $tmp = explode('/', str_replace(MODX_BASE_PATH, '', MODX_ASSETS_PATH . $path));
        $dir = rtrim(MODX_BASE_PATH, '/');
        foreach ($tmp as $v) {
            if (empty($v)) {
                continue;
            }
            $dir .= '/' . $v;
            if (!file_exists($dir) || !is_dir($dir)) {
                @mkdir($dir);
            }
        }
        if (!file_exists(MODX_ASSETS_PATH . $path) || !is_dir(MODX_ASSETS_PATH . $path)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR,
                '[Office] Could not create images dir "' . MODX_ASSETS_PATH . $path . '"');

            return false;
        }

        // Remove image
        if (!empty($this->_current_photo) && isset($_POST['photo']) && empty($_POST['photo'])) {
            $tmp = explode('/', $this->_current_photo);
            if (!empty($tmp[1])) {
                $cur = MODX_ASSETS_PATH . $path . end($tmp);
                if (!empty($cur) && file_exists($cur)) {
                    @unlink($cur);
                }
            }
            $this->object->Profile->set('photo', '');
            // Upload a new one
        } elseif (!empty($_FILES['newphoto']) && preg_match('/image/',
                $_FILES['newphoto']['type']) && $_FILES['newphoto']['error'] == 0
        ) {
            move_uploaded_file($_FILES['newphoto']['tmp_name'], $dst);

            if (!class_exists('modPhpThumb')) {
                /** @noinspection PhpIncludeInspection */
                require MODX_CORE_PATH . 'model/phpthumb/modphpthumb.class.php';
            }
            $phpThumb = new modPhpThumb($this->modx);
            $phpThumb->initialize();

            $phpThumb->setSourceFilename($dst);
            foreach ($params as $k => $v) {
                $phpThumb->setParameter($k, $v);
            }
            if ($phpThumb->GenerateThumbnail()) {
                if ($phpThumb->RenderToFile($dst)) {
                    if (!empty($cur) && file_exists($cur)) {
                        @unlink($cur);
                    }
                    $this->object->Profile->set('photo', $url);
                } else {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, '[Office] Could not save rendered image to "' . $dst . '"');
                }
            } else {
                $this->modx->log(modX::LOG_LEVEL_ERROR, '[Office] ' . print_r($phpThumb->debugmessages, true));
            }
        }

        return true;
    }


    /**
     * @return bool
     */
    public function afterSave()
    {
        if ($this->_new_email != $this->_current_email) {
            $this->object->Profile->set('email', $this->_current_email);
            $this->object->Profile->save();
        }
        if ($this->_new_phone != $this->_current_phone && $this->modx->getOption('office_auth_mode') == 'phone') {
            $this->object->Profile->set('mobilephone', $this->_current_phone);
            $this->object->Profile->save();
        }

        return parent::afterSave();
    }

}

return 'officeProfileUserUpdateProcessor';