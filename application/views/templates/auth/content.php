<div class="forms-container">
    <div class="signin-signup">

        <?= form_open('auth/signInHelpdesk', ['class' => 'formsignin user sign-in-form']); ?>
        <form action="" class="user sign-in-form" method="post">
            <h2 class="title">Sign in</h2>
            <div class="input-field">
                <i class="fas fa-user"></i>
                <input type="text" id="username_signin" name="username_signin" placeholder="Username" />
            </div>
            <div class="input-field">
                <i class="fas fa-lock"></i>
                <input type="password" id="password_signin" name="password_signin" placeholder="Password" />
            </div>
            <input type="submit" value="Sign In" class="btn solid" id="btnSignin" name="btnSignin" />
        </form>
        <?= form_close(); ?>

        <?= form_open('auth/loginHelpdesk', ['class' => 'formlogin user sign-up-form']); ?>
        <div class="pesan" style="display: none;"></div>
        <h2 class="title">Helpdesk 2.1</h2>
        <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" id="username" name="username" placeholder="Username" />
        </div>
        <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" id="password" name="password" placeholder="Password" />
        </div>
        <input type="submit" class="btn" value="Log In" id="btnLogin" name="btnLogin" />
        <?= form_close(); ?>
    </div>
</div>

<div class="panels-container">
    <div class="panel left-panel">
        <div class="content">
            <h3>Helpdesk 2.1</h3>
            <p>
                Login disini apabila ingin menyelesaikan tiket request
            </p>
            <button class="btn transparent" id="sign-up-btn">
                Click Me !
            </button>
        </div>
        <img src="<?= base_url('assets'); ?>/img/doctor.svg" class="image" alt="" />
    </div>
    <div class="panel right-panel">
        <div class="content">
            <h3>Create tiket ?</h3>
            <p>
                Klik tombol disini apabila ingin membuat permohonan tiket
            </p>
            <button type="button" class="btn transparent" id="sign-in-btn">
                Click Me !
            </button>
        </div>
        <img src=" <?= base_url('assets'); ?>/img/ict.svg" class="image" alt="" />
    </div>
</div>