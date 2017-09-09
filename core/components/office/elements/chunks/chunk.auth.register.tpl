[[+code:isnot=``:then=`
    [[++cultureKey:is=`ru`:then=`
        Вы зарегистрировались на [[++site_name]], ваш пароль: [[+password]]. Ваш код активации: [[+code]].
    `:else=`
        You registered on [[++site_name]], your password: [[+password]]. Your activation code: [[+code]].
    `]]
`:else=`
    [[++cultureKey:is=`ru`:then=`
        <p>Вы успешно зарегистрировались на сайте [[++site_name]], указав email [[+email]].</p>
        <p>Теперь вам нужно <a href="[[+link]]">пройти по ссылке</a>, чтобы активировать учётную запись.<br/>
            Ваш пароль: <strong>[[+password]]</strong>.</p>
    `:else=`
        <p>You have successfully registered at [[++site_name]] using email [[+email]].</p>
        <p>Now you need <a href="[[+link]]">follow the link</a> to activate your account.<br/>
            Your password is: <strong>[[+password]]</strong>.</p>
    `]]
`]]