<?php

$lang['auth_login_incorrect_password'] = "Неправильный пароль.";
$lang['auth_login_username_not_exist'] = "Пользователь не найден.";

$lang['auth_username_or_email_not_exist'] = "Пользователь или адрес e-mail не существуют.";
$lang['auth_not_activated'] = "Ваш аккаунт пока не активирован. Пожалуйста проверьте ваш e-mail.";
$lang['auth_request_sent'] = "Ваш запрос на смену пароля уже принят. Пожалуйста проверьте ваш e-mail.";
$lang['auth_incorrect_old_password'] = "Ваш старый пароль неверный.";
$lang['auth_incorrect_password'] = "Ваш пароль неверный.";

// Email subject
$lang['auth_account_subject'] = "%s account details";
$lang['auth_activate_subject'] = "%s активация аккаунта";
$lang['auth_forgot_password_subject'] = "Заявка на новый пароль";

// Email content
$lang['auth_account_content'] = "Добро пожаловать на сайт %s,

Спасибо за регистрацию на РуАвторе. Ваш аккаунт успешно активирован, но нужно чтобы его одобрил Администратор.
Для этого напишите письмо на адрес ruavtor.ru@gmail.com и процитируйте данное сообщение.

Для входа на сайт вы можете использовать как логин, так и email.
Используйте для входа следующие реквизиты:

Логин: %s
Email: %s
Пароль: %s

Мы надеемся, что вам понравится ваше пребывание на сайте :)

С уважением ,
администрация сайта %s";

$lang['auth_activate_content'] = "Добро пожаловать на сайт %s,

Для активации вашего аккаунта вы должны пройти по данной ссылке:
%s

Пожалуйста, активируйте ваш аккаунт не позднее чем через %s часов, иначе ваша регистрация будет признана недействительной и вам придется регистрироваться заново.
После активации ваш аккаунт должен пройти процедуру одобрения администратором.
Для входа на сайт вы можете использовать как логин, так и email.
Используйте для входа следующие реквизиты:

Логин: %s
Email: %s
Пароль: %s

Мы надеемся, что вам понравится ваше пребывание на сайте :)

С уважением ,
администрация сайта %s";

$lang['auth_forgot_password_content'] = "%s,

Если вы запрашивали смену пароля прощу вас пройти по следующей ссылке: %s

Ваш новый пароль: %s
Ключ для активации: %s

After you successfully complete the process, you can change this new password into password that you want.

If you have any more problems with gaining access to your account please contact %s.

С уважением ,
администрация сайта %s";

/* End of file dx_auth_lang.php */
/* Location: ./application/language/english/dx_auth_lang.php */