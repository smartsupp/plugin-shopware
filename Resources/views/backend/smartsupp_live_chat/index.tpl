{extends file="parent:backend/_base/layout.tpl"}

{block name="content/main"}
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,700&amp;subset=latin,latin-ext" rel="stylesheet" type="text/css">

    <div class="wrap" id="content">
        {if $active}
        <main role="main">
            <div id="third">
                <section class="top-bar">
                    <div class="text-center">
                        <img src="{link file='backend/_resources/images/logo.png'}" alt="smartsupp logo" />
                    </div>
                </section>
                <section class="deactivate">
                    <div class="row">
                        <p class="bold">
                            <span class="left">{$email}</span>
                            <span class="right"><a class="js-action-disable" href="javascript: void(0);">Deactivate Chat</a></span>
                        </p>
                        <div class="clear"></div>
                        <section class="intro">
                            <h4>
                                <strong class="green">Smartsupp’s chat box is now visible on your website.</strong> <br /><br />
                                Go to Smartsupp to chat with visitors, customize chat box design and access all features.
                            </h4>
                            <div class="intro--btn">
                                <a href="https://dashboard.smartsupp.com?utm_source=Shopware&utm_medium=integration&utm_campaign=link" target="_blank" class="js-register btn btn-primary btn-xl">Go to Smartsupp</a>
                            </div>
                            <p class="tiny text-center bigger-m">(This will open a new browser tab)</p>
                        </section>
                    </div>
                </section>
                <section>
                    <div class="settings-container">
                        <div class="section--header">
                            <h3 class="no-margin bold">Advanced settings</h3>
                            <p>Don't put the chat code here — this box is for (optional) advanced customizations via <a href="https://developers.smartsupp.com?utm_source=Magento&utm_medium=integration&utm_campaign=link" target="_blank">Smartsupp API</a></p>
                        </div>
                        <div class="section--body">
                            <form action="" method="post" id="settingsForm" class="js-code-form form-horizontal" autocomplete="off">
                                <div class="alerts">
                                    {if $message}
                                        <div class="alert alert-success">
                                            {$message}
                                        </div>
                                    {/if}
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <textarea name="code" id="textAreaSettings" cols="30" rows="10">{$optionalCode}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <div class="loader"></div>
                                        <button type="submit" class="btn btn-primary btn-lg" name="_submit">
                                            Save changes
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </main>
        {else}
        <main role="main" class="sections" id="home" {if $formAction}style="display: none;"{/if}>
        <div id="first">
            <section class="top-bar">
                <div class="">
                    <img src="{link file='backend/_resources/images/logo.png'}" alt="smartsupp logo" />
                    <a href="javascript: void(0);" class="js-login btn btn-default">Connect existing account</a>
                </div>
            </section>
            <section class="intro">
                <div class="">
                    <h1 class="lead">Free live chat with visitor recording</h1>
                    <h3>Your customers are on your website right now. <br/>Chat with them and see what they do.</h3>
                    <div class="intro--btn">
                        <a href="javascript: void(0);" class="js-register btn btn-primary btn-xl">Create a free account</a>
                    </div>
                    <div class="intro--image">
                        <img src="https://www.smartsupp.com/assets/images/dash/en.png" alt="intro" />
                    </div>
                </div>
            </section>
            <section>
                <div class=" text-center">
                    <div class="section--header">
                        <h2>Enjoy unlimited agents and chats forever for free<br />or take advantage of premium packages with advanced features.</h2>
                        <p><strong>See all features on </strong><a href="https://www.smartsupp.com?utm_source=Shopware&utm_medium=integration&utm_campaign=link" target="_blank"> our website.</a></p>
                    </div>
                    <div class="section--body boxies">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 box box-bubble">
                                <p class="bold">Chat with visitors in real-time</p>
                                <p class="tiny">Answering questions right away improves loyalty and helps you build closer relationships with your customers.</p>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 box box-graph">
                                <p class="bold">Increase online sales</p>
                                <p class="tiny">Turn your visitors into customers.<br />Visitors who chat with you buy up to 5x more often - measurable in Google Analytics.</p>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 box box-mouse">
                                <p class="bold">Visitor screen recording</p>
                                <p class="tiny">Watch visitor's behavior on your store.<br />You see his screen, mouse movement, clicks and what he filled into forms.</p>
                            </div>
                        </div>
                    </div>
                    <div class="section--header">
                        <h2>Trusted by more than 270 000 companies</h2>
                    </div>
                    <div class="section--body">
                        <div class="customers">
                            <img class="partner" src="{link file='backend/_resources/images/skoda.png'}" alt="ŠKODA AUTO a.s." />
                            <img class="partner" src="{link file='backend/_resources/images/gekko.png'}" alt="GEKKO Computer" />
                            <img class="partner" src="{link file='backend/_resources/images/lememo.png'}" alt="Lememo" />
                            <img class="partner" src="{link file='backend/_resources/images/conrad.png'}" alt="Conrad" />
                        </div>
                    </div>
                </div>
            </section>
        </div>
        </main>
        <main role="main" class="sections" id="connect" {if !$formAction}style="display: none;"{/if}>
        <div id="second">
            <section class="top-bar">
                <div>
                    <a href="javascript: void(0);" class="js-close-form">
                        <img src="{link file='backend/_resources/images/logo.png'}" alt="smartsupp logo" />
                        <a href="javascript: void(0);" class="btn btn-default" data-toggle-form data-multitext data-register="Connect existing account" data-login="Create a free account">
                            {if $formAction == "register"}Connect existing account{else}Create a free account{/if}
                        </a>
                    </a>
                </div>
            </section>
            <section id="signUp">
                <div class="text-center">
                    <div class="form-container">
                        <div class="section--header">
                            <h1 class="lead" data-multitext data-login="Connect existing account" data-register="Create a free account">
                                {if $formAction == "login"}Connect existing account{else}Create a free account{/if}
                            </h1>
                        </div>
                        <div class="section--body">
                            <div class="form--inner">
                                <form action="" method="post" id="signUpForm" class="form-horizontal{if $formAction} js-{$formAction}-form{/if}" data-toggle-class autocomplete="off">
                                    <div class="alerts">
                                        {if $message}
                                            <div class="alert alert-danger js-clear">
                                                {$message}
                                            </div>
                                        {/if}
                                    </div>
                                    <div class="form-group">
                                        <label class="visible-xs control-label col-xs-12" for="frm-signUp-form-email">E-mail</label>
                                        <div class="col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon hidden-xs" style="min-width: 150px;">E-mail</span>
                                                <input type="email" class="form-control input-lg" name="email" id="frm-signUp-form-email" required="" value="{$email}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="visible-xs control-label col-xs-12" for="frm-signUp-form-password">Password</label>
                                        <div class="col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon hidden-xs" style="min-width: 150px;">Password</span>
                                                <input type="password" class="form-control input-lg" name="password" autocomplete="off" id="frm-signUp-form-password" required="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="gdpr checkbox">
                                        <label for="frm-signUp-form-termsConsent">
                                            <input {if $termsConsent}checked="checked"{/if} value="1" type="checkbox" name="termsConsent" id="frm-signUp-form-termsConsent" required="">&nbsp;I have read and agree with <a href="https://www.smartsupp.com/terms" target="_blank">Terms</a> and <a href="https://www.smartsupp.com/dpa" target="_blank">DPA</a>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12 form-button">
                                            <div class="loader"></div>
                                            <button type="submit" class="btn btn-primary btn-lg btn-block" name="_submit" data-multitext data-login="Connect account" data-register="Sign up">
                                                {if $formAction == "login"}Connect account{else}Sign up{/if}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="section--header">
                        <h2>Trusted by more than 270 000 companies</h2>
                    </div>
                    <div class="section--body">
                        <div class="customers">
                            <img class="partner" src="{link file='backend/_resources/images/skoda.png'}" alt="ŠKODA AUTO a.s." />
                            <img class="partner" src="{link file='backend/_resources/images/gekko.png'}" alt="GEKKO Computer" />
                            <img class="partner" src="{link file='backend/_resources/images/lememo.png'}" alt="Lememo" />
                            <img class="partner" src="{link file='backend/_resources/images/conrad.png'}" alt="Conrad" />
                        </div>
                    </div>
                </div>
            </section>
        </div>
        </main>
        {/if}
    </div>
{/block}