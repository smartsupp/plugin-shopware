$(document).ready(function () {
    var lastOpen = null;

    function openForm(type) {
        lastOpen = type;
        updateTexts(type);
        updateClass(type);
        $('#home').hide();
        $('#connect').show();
        $('.js-clear').hide();
        showGdpr(type == 'register');
    }

    function closeForm() {
        $('#connect').hide();
        $('#home').show();
    }

    function updateClass(type) {
        if (type === 'login') {
            $('[data-toggle-class]').addClass('js-login-form').removeClass('js-register-form');
        } else {
            $('[data-toggle-class]').removeClass('js-login-form').addClass('js-register-form');
        }
    }

    function updateTexts(type) {
        $('[data-multitext]').each(function () {
            $(this).text($(this).data(type));
        })
    }

    function showGdpr(show) {
        var $gdprContainer = $('.gdpr.checkbox');
        var $checkbox = $gdprContainer.find('input');

        if (show) {
            $gdprContainer.show();
            $checkbox.prop('disabled', false);
        } else {
            $gdprContainer.hide();
            $checkbox.prop('disabled', true);
        }
    }

    var $content = $('#content'), $document = $(document);

    function reloadContent(url, params, finishFunction) {
        $.get(url, params, function(data){
            $content.html($('<div/>').html(data).find('#content').html());

            if (finishFunction !== undefined) {
                finishFunction();
            }
        });
    }

    $document.on('click', '.js-login', function () {
        openForm('login');
    }).on('click', '.js-register', function () {
        openForm('register');
    }).on('click', '[data-toggle-form]', function () {
        if (lastOpen === 'login') {
            openForm('register');
        } else {
            openForm('login');
        }
    }).on('click', '.js-close-form', function () {
        closeForm();
    }).on('click', '.js-action-disable', function () {
        reloadContent('?ssaction=disable#content');
    }).on('submit', '.js-login-form', function (event) {
        event.preventDefault();
        var $loader = $(this).find('.loader'), $button = $(this).find('button'), $loginForm = $('.js-login-form');
        $button.hide();
        $loader.show();

        reloadContent('?ssaction=login#content', {
            email: $loginForm.find('input[name="email"]').val(),
            password: $loginForm.find('input[name="password"]').val()
        }, function () {
            $loader.hide();
            $button.show();
        });
    }).on('submit', '.js-register-form', function (event) {
        event.preventDefault();
        var $loader = $(this).find('.loader'), $button = $(this).find('button'), $registerForm = $('.js-register-form');
        $button.hide();
        $loader.show();
        reloadContent('?ssaction=register#content', {
            email: $registerForm.find('input[name="email"]').val(),
            password: $registerForm.find('input[name="password"]').val()
        }, function () {
            $loader.hide();
            $button.show();
        });
    }).on('submit', '.js-code-form', function (event) {
        event.preventDefault();
        var $loader = $(this).find('.loader'), $button = $(this).find('button'), $codeForm = $('.js-code-form');
        $button.hide();
        $loader.show();
        reloadContent('?ssaction=update#content', {
            code: $codeForm.find('textarea[name="code"]').val()
        }, function () {
            $loader.hide();
            $button.show();
        });
    });
});
