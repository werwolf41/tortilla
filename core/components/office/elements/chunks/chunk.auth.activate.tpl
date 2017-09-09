[[+code:isnot=``:then=`
    [[++cultureKey:is=`ru`:then=`
        Для активации пароля [[+password]], укажите секретный код: [[+code]].
    `:else=`
        To activate the password [[+password]], specify a secret code: [[+code]].
    `]]
`:else=`
    [[++cultureKey:is=`ru`:then=`
        <p>Вы (или кто-то другой) запросил сброс пароля для вашей учётной записи на сайте [[++site_name]].</p>
        <p>Если это действительно были вы, то вам нужно <a href="[[+link]]">пройти по ссылке</a>,
            для активации нового пароля: <strong>[[+password]]</strong><br/>
            В противном случае, просто удалите это письмо.</p>
    `:else=`
        <p>You (or someone else) has requested a password reset for your account at [[++site_name]].</p>
        <p>If it really was you, then you need <a href="[[+link]]">follow the link</a>,
            to activate the new password: <strong>[[+password]]</strong><br/>
            Otherwise, just delete this message.</p>
    `]]
`]]