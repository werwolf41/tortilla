[[+code:isnot=``:then=`
    [[++cultureKey:is=`ru`:then=`
        Ваш код подтверждения нового номера: [[+code]].
    `:else=`
        Your confirmation code: [[+code]].
    `]]
`:else=`
    [[++cultureKey:is=`ru`:then=`
        <p>Вы изменили email в своём профиле на сайте [[++site_name]].</p>
        <p>Для подтверждения нового адреса вам нужно <a href="[[+link]]">пройти по ссылке</a>.</p>
    `:else=`
        <p>You have changed email in yours profile on the website [[++site_name]].</p>
        <p>To confirm the new address you need <a href="[[+link]]">follow the link</a>.</p>
    `]]
`]]