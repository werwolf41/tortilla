<?php

class officeProfileController extends officeDefaultController
{

    /**
     * @param array $config
     */
    public function setDefault($config = array())
    {
        if (defined('MODX_ACTION_MODE') && MODX_ACTION_MODE && !empty($_SESSION['Office']['Profile'][$this->modx->context->key])) {
            $this->config = $_SESSION['Office']['Profile'][$this->modx->context->key];
            $this->config['json_response'] = true;
        } else {
            $this->config = array_merge(array(
                'tplProfile' => 'tpl.Office.profile.form',
                'tplActivate' => 'tpl.Office.profile.activate',

                'profileFields' => 'username:50,email:50,fullname:50,phone:12,mobilephone:12,dob:10,gender,address,country,city,state,zip,fax,photo,comment,website,specifiedpassword,confirmpassword',
                'requiredFields' => 'username,email,fullname',

                'linkTTL' => 600,
                'HybridAuth' => true,
                'providerTpl' => 'tpl.HybridAuth.provider',
                'activeProviderTpl' => 'tpl.HybridAuth.provider.active',

                'page_id' => $this->modx->getOption('office_profile_page_id'),

                'avatarPath' => 'images/users/',
                'avatarParams' => '{"w":200,"h":200,"zc":0,"bg":"ffffff","f":"jpg"}',

                'gravatarUrl' => 'https://gravatar.com/avatar/',
            ), $config);
        }

        // Save main page_id if not exists. It will be used for another contexts that has no snippet call
        /** @var modSystemSetting $setting */
        if (!$setting = $this->modx->getObject('modSystemSetting', 'office_profile_page_id')) {
            $setting = $this->modx->newObject('modSystemSetting');
            $setting->set('key', 'office_profile_page_id');
            $setting->set('value', $this->modx->resource->id);
            $setting->set('namespace', 'office');
            $setting->set('area', 'office_profile');
            $setting->save();
        }

        $_SESSION['Office']['Profile'][$this->modx->context->key] = $this->config;
    }


    /**
     * Returns array with language topics
     *
     * @return array
     */
    public function getLanguageTopics()
    {
        return array('office:profile', 'core:user');
    }


    /**
     * Returns profile form
     *
     * @return string
     */
    public function defaultAction()
    {
        if ($this->modx->resource->id && $this->modx->resource->id != $this->config['page_id']) {
            // Save page_id for current context
            /** @var modContextSetting $setting */
            $key = array('key' => 'office_profile_page_id', 'context_key' => $this->modx->context->key);
            if (!$setting = $this->modx->getObject('modContextSetting', $key)) {
                $setting = $this->modx->newObject('modContextSetting');
            }
            // It will be updated on every snippet call
            $setting->fromArray($key, '', true, true);
            $setting->set('value', $this->modx->resource->id);
            $setting->set('namespace', 'office');
            $setting->set('area', 'office_profile');
            $setting->save();

            $this->config['page_id'] = $this->modx->resource->id;
        }

        if (!$this->modx->user->isAuthenticated($this->modx->context->key)) {
            return $this->modx->user->isAuthenticated('mgr')
                ? $this->modx->lexicon('office_err_mgr_auth')
                : '';
        }

        $config = $this->office->makePlaceholders($this->office->config);
        if ($css = trim($this->modx->getOption('office_profile_frontend_css', null,
            '[[+cssUrl]]profile/default.css'))
        ) {
            $this->modx->regClientCSS(str_replace($config['pl'], $config['vl'], $css));
        }
        if ($js = trim($this->modx->getOption('office_profile_frontend_js', null, '[[+jsUrl]]profile/default.js'))) {
            $this->modx->regClientScript(str_replace($config['pl'], $config['vl'], $js));
        }

        $pls = array();
        if ($this->config['HybridAuth'] && file_exists(MODX_CORE_PATH . 'components/hybridauth/')) {
            if ($this->modx->loadClass('hybridauth', MODX_CORE_PATH . 'components/hybridauth/model/hybridauth/', false,
                true)
            ) {
                $HybridAuth = new HybridAuth($this->modx, $this->config);
                $HybridAuth->initialize($this->modx->context->key);
                $pls['providers'] = $HybridAuth->getProvidersLinks($this->config['providerTpl'],
                    $this->config['activeProviderTpl']);
            }
        }

        if ($this->modx->resource->id != $this->config['page_id']) {
            /** @var modContextSetting $setting */
            $key = array('key' => 'office_profile_page_id', 'context_key' => $this->modx->context->key);
            if (!$setting = $this->modx->getObject('modContextSetting', $key)) {
                $setting = $this->modx->newObject('modContextSetting');
                $setting->fromArray($key, '', true, true);
            }
            $setting->set('value', $this->modx->resource->id);
            $setting->save();
            $this->config['page_id'] = $this->modx->resource->id;
        }

        $user = $this->modx->user->toArray();
        $profile = $this->modx->user->Profile->toArray();
        $pls = array_merge($pls, $profile, $user);
        if (!empty($pls['dob'])) {
            $pls['dob'] = date('Y-m-d', $pls['dob']);
        }
        if (!empty($_GET['off_req'])) {
            $required = explode('-', $_GET['off_req']);
            foreach ($required as $v) {
                $pls['error_' . $v] = $this->modx->lexicon('office_profile_err_field_' . $v);
            }
        }
        $pls['gravatar'] = $this->config['gravatarUrl'] . md5(strtolower($profile['email']));

        return $this->office->getChunk($this->config['tplProfile'], $pls);
    }


    /**
     * Updates profile of user
     *
     * @param array $data
     *
     * @return array|string
     */
    public function Update($data = array())
    {
        if (!$this->modx->user->isAuthenticated($this->modx->context->key)) {
            return $this->error($this->modx->lexicon('office_err_auth'));
        }

        if (!empty($data['phone_code'])) {
            $confirm = $this->confirmPhone($data['phone_code']);

            return $confirm !== true
                ? $this->error($confirm)
                : $this->success($this->modx->lexicon('office_profile_msg_phone_confirm'), array(
                    'phone_code' => false,
                ));
        }

        $requiredFields = !empty($this->config['requiredFields'])
            ? array_map('trim', explode(',', $this->config['requiredFields']))
            : array();
        $profileFields = array();
        if ($this->modx->getOption('office_auth_mode') == 'phone') {
            $requiredFields[] = 'mobilephone';
        }

        $fields = array(
            'requiredFields' => $requiredFields,
            'avatarPath' => $this->config['avatarPath'],
            'avatarParams' => $this->config['avatarParams'],
        );

        $tmp = array_map('trim', explode(',', $this->config['profileFields']));
        foreach ($tmp as $field) {
            if (preg_match('#:\d+$#', $field)) {
                list($key, $length) = explode(':', $field);
            } else {
                $key = $field;
                $length = 0;
            }
            $profileFields[$key] = $length;
        }

        foreach ($requiredFields as $field) {
            if (!isset($profileFields[$field])) {
                $profileFields[$field] = 0;
            }
        }

        foreach ($profileFields as $field => $length) {
            if (isset($data[$field])) {
                if ($field == 'comment') {
                    $fields[$field] = $this->modx->stripTags(
                        empty($length)
                            ? trim($data[$field])
                            : substr(trim($data[$field]), 0, $length)
                    );
                } elseif ($field == 'specifiedpassword' || $field == 'confirmpassword') {
                    $fields[$field] = $this->modx->stripTags($data[$field]);
                } else {
                    $fields[$field] = $this->Sanitize($data[$field], $length);
                }
            } // Extended fields
            elseif (preg_match_all('#(.*?)(\[.*?\]+)#', $field, $matches)) {
                if (isset($data[$matches[1][0]])) {
                    $tmp = $data[$matches[1][0]];
                    $keys = array();
                    $count = count($matches[2]);
                    for ($i = 0; $i <= $count; $i++) {
                        $key = trim($matches[2][$i], '[]');
                        $length = 0;
                        if (preg_match('#:\d+$#', $key)) {
                            list($key, $length) = explode(':', $key);
                        }
                        $keys[] = $key;
                        // Last key
                        if ($i + 1 == $count) {
                            $value = &$fields[$matches[1][0]];
                            foreach ($keys as $k) {
                                if (!isset($value[$k])) {
                                    $value[$k] = array();
                                }
                                $value = &$value[$k];
                            }
                            $value = $this->Sanitize($tmp[$key], $length);
                            unset($value);
                        } // Get value on current level
                        elseif (isset($tmp[$key])) {
                            $tmp = $tmp[$key];
                        } else {
                            break;
                        }
                    }
                }
            }
        }

        $changeEmail = $changePhone = false;
        $new_email = $new_phone = '';
        if (!empty($fields['email'])) {
            $current_email = $this->modx->user->Profile->get('email');
            $new_email = trim($fields['email']);
            $changeEmail = strtolower($current_email) != strtolower($new_email);
        }
        if (!empty($fields['mobilephone']) && $this->modx->getOption('office_auth_mode') == 'phone') {
            $current_phone = $this->office->checkPhone($this->modx->user->Profile->get('mobilephone'));
            $new_phone = $this->office->checkPhone($fields['mobilephone']);
            $changePhone = $current_phone != $new_phone;
        }

        /** @var modProcessorResponse $response */
        $response = $this->office->runProcessor('profile/update', $fields);
        if ($response->isError()) {
            $message = $response->hasMessage()
                ? $response->getMessage()
                : $this->modx->lexicon('office_profile_err_update');
            $errors = array();
            if ($response->hasFieldErrors()) {
                /**@var modProcessorResponseError $tmp */
                if ($tmp = $response->getFieldErrors()) {
                    foreach ($tmp as $error) {
                        if (!empty($error->field)) {
                            $errors[$error->field] = $error->message;
                        } else {
                            $errors = array_replace_recursive($errors, $error->error);
                        }
                    }
                }
            }

            return $this->error($message, $errors);
        }

        $message = array(
            $this->modx->lexicon('office_profile_msg_save'),
        );
        if ($changeEmail && !empty($new_email)) {
            $page_id = !empty($data['pageId'])
                ? $data['pageId']
                : $this->modx->getOption('office_profile_page_id');

            $change = $this->changeEmail($new_email, $page_id);
            $message[] = ($change === true)
                ? $this->modx->lexicon('office_profile_msg_save_email')
                : $this->modx->lexicon('office_profile_msg_save_noemail', array('errors' => $change));
        }
        if ($changePhone && !empty($new_phone)) {
            $change = $this->changePhone($new_phone);
            $message[] = ($change === true)
                ? $this->modx->lexicon('office_profile_msg_save_phone')
                : $this->modx->lexicon('office_profile_msg_save_nophone', array('errors' => $change));
        }

        $object = $response->getObject();
        if (!empty($object['specifiedpassword'])) {
            $message[] = $this->modx->lexicon('office_profile_msg_save_password',
                array('password' => $object['specifiedpassword'])
            );
        }

        $saved = array();
        $user = $this->modx->getObject('modUser', $this->modx->user->id);
        $profile = $this->modx->getObject('modUserProfile', array('internalKey' => $this->modx->user->id));
        $tmp = array_merge($profile->toArray(), $user->toArray());
        if (!empty($changeEmail)) {
            $tmp['email'] = $new_email;
        }
        if (!empty($changePhone)) {
            $tmp['mobilephone'] = $new_phone;
            $saved['phone_code'] = true;
        } else {
            $saved['phone_code'] = false;
        }
        foreach ($fields as $k => $v) {
            if (isset($tmp[$k]) && isset($data[$k])) {
                if ($k == 'dob') {
                    $saved[$k] = date('Y-m-d', $tmp[$k]);
                } else {
                    $saved[$k] = $tmp[$k];
                }
            }
        }

        return $this->success(implode('<br>', $message), $saved);
    }


    /**
     * Sanitizes a string
     *
     * @param string|array $input The string to sanitize
     * @param integer $length The length of sanitized string
     *
     * @return string|array The sanitized string or array with strings.
     */
    public function Sanitize($input = '', $length = 0)
    {
        if (is_array($input)) {
            $output = array();
            foreach ($input as $key => $value) {
                $output[$key] = $this->Sanitize($value, $length);
            }
        } else {
            $expr = $this->modx->getOption('office_sanitize_pcre', null, '/[^-_0-9\p{L}\s@.,:\/\\+()]+/iu', true);
            $string = html_entity_decode($input, ENT_QUOTES, 'UTF-8');
            $output = trim(preg_replace($expr, '', $string));

            return !empty($length)
                ? mb_substr($output, 0, $length, 'UTF-8')
                : $output;
        }

        return $output;
    }


    /**
     * Method for change email of user
     *
     * @param $email
     * @param $page_id
     *
     * @return bool
     */
    public function changeEmail($email, $page_id)
    {
        if (!$this->modx->user->isAuthenticated()) {
            return false;
        }
        $hash = md5(uniqid(md5($this->modx->user->Profile->get('email') . '/' . $this->modx->user->get('id')), true));
        /** @var modDbRegister $register */
        $register = $this->modx->getService('registry', 'registry.modRegistry')
            ->getRegister('user', 'registry.modDbRegister');
        $register->connect();
        $register->subscribe('/email/change/');
        $register->send('/email/change/', array(
            $hash => array(
                'email' => $email,
                'user' => $this->modx->user->id,
            ),
        ), array('ttl' => $this->config['linkTTL']));

        $link = $this->modx->makeUrl($page_id, '', array(
            'action' => 'profile/confirmEmail',
            'hash' => $hash,
        ), 'full');

        $content = $this->office->getChunk($this->config['tplActivate'],
            array_merge(
                $this->modx->user->getOne('Profile')->toArray(),
                $this->modx->user->toArray(),
                array(
                    'link' => $link,
                    'code' => '',
                )
            )
        );
        $maxIterations = (int)$this->modx->getOption('parser_max_iterations', null, 10);
        $this->modx->getParser()->processElementTags('', $content, false, false, '[[', ']]', array(), $maxIterations);
        $this->modx->getParser()->processElementTags('', $content, true, true, '[[', ']]', array(), $maxIterations);

        /** @var modPHPMailer $mail */
        $mail = $this->modx->getService('mail', 'mail.modPHPMailer');
        $mail->set(modMail::MAIL_BODY, trim($content));
        $mail->set(modMail::MAIL_FROM, $this->modx->getOption('emailsender'));
        $mail->set(modMail::MAIL_FROM_NAME, $this->modx->getOption('site_name'));
        $mail->set(modMail::MAIL_SENDER, $this->modx->getOption('emailsender'));
        $mail->set(modMail::MAIL_SUBJECT, $this->modx->lexicon('office_profile_email_subject'));
        $mail->address('to', $email);
        $mail->address('reply-to', $this->modx->getOption('emailsender'));
        $mail->setHTML(true);
        $response = !$mail->send()
            ? $mail->mailer->ErrorInfo
            : true;
        $mail->reset();

        return $response;
    }


    /**
     * @param $phone
     *
     * @return array|bool|string
     */
    public function changePhone($phone)
    {
        if (!$this->modx->user->isAuthenticated()) {
            return false;
        }
        $code = rand(100000, 999999);
        /** @var modDbRegister $register */
        $register = $this->modx->getService('registry', 'registry.modRegistry')
            ->getRegister('user', 'registry.modDbRegister');
        $register->connect();
        $register->subscribe('/phone/change/');
        $register->send('/phone/change/', array(
            md5($this->modx->user->id . $code) => array(
                'phone' => $phone,
                'user' => $this->modx->user->id,
            ),
        ), array('ttl' => $this->config['linkTTL']));

        $content = $this->office->getChunk($this->config['tplActivate'],
            array_merge(
                $this->modx->user->getOne('Profile')->toArray(),
                $this->modx->user->toArray(),
                array(
                    'code' => $code,
                    'link' => '',
                )
            )
        );
        $maxIterations = (int)$this->modx->getOption('parser_max_iterations', null, 10);
        $this->modx->getParser()->processElementTags('', $content, false, false, '[[', ']]', array(), $maxIterations);
        $this->modx->getParser()->processElementTags('', $content, true, true, '[[', ']]', array(), $maxIterations);

        /** @var SmsRu|ByteHand $provider */
        $provider = $this->modx->getService(
            $this->modx->getOption('office_sms_provider'),
            $this->modx->getOption('office_sms_provider'),
            $this->modx->getOption('office_sms_provider_path', null,
                $this->office->config['corePath'] . 'model/sms/')
        );
        if (is_object($provider) && method_exists($provider, 'send')) {
            $send = $provider->send($phone, trim($content));
            if ($send !== true) {
                $this->modx->log(modX::LOG_LEVEL_ERROR,
                    '[Office] Unable to send sms to ' . $phone . '. Response is: ' . $send
                );

                return $this->modx->lexicon('office_auth_err_sms', array('errors' => $send));
            }
        } else {
            return $this->modx->lexicon('office_auth_err_sms_provider');
        }

        return true;
    }


    /**
     * Method for confirmation of user email
     *
     * @param $data
     */
    public function confirmEmail($data)
    {
        if (!empty($data['hash'])) {
            /** @var modDbRegister $register */
            $register = $this->modx->getService('registry', 'registry.modRegistry')
                ->getRegister('user', 'registry.modDbRegister');
            $register->connect();
            $register->subscribe('/email/change/' . $data['hash']);
            $msgs = $register->read(array('poll_limit' => 1));
            if (!empty($msgs[0])) {
                $msgs = reset($msgs);
                /** @var modUser $user */
                if (!empty($msgs['user']) && $user = $this->modx->getObject('modUser', (int)$msgs['user'])) {
                    /** @var modUserProfile $profile */
                    if (!empty($msgs['email']) && $profile = $user->getOne('Profile')) {
                        $profile->set('email', $msgs['email']);
                        $profile->save();
                    }
                }
            }
        }

        $this->modx->sendRedirect($this->modx->makeUrl($this->modx->resource->id, '', '', 'full'));
    }


    public function confirmPhone($code)
    {
        if (!$this->modx->user->isAuthenticated()) {
            return false;
        }
        /** @var modDbRegister $register */
        $register = $this->modx->getService('registry', 'registry.modRegistry')
            ->getRegister('user', 'registry.modDbRegister');
        $register->connect();
        $register->subscribe('/phone/change/' . md5($this->modx->user->id . $code));
        $msgs = $register->read(array('poll_limit' => 1));
        if (!empty($msgs[0])) {
            $msgs = reset($msgs);
            if (!empty($msgs['phone'])) {
                $this->modx->user->Profile->set('mobilephone', $msgs['phone']);
                $this->modx->user->Profile->save();

                return true;
            }
        }

        return $this->modx->lexicon('office_profile_err_phone_code');
    }

}

return 'officeProfileController';
