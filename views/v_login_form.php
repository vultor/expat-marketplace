<!-- This is the Log in page -->
  <section data-role="page" data-theme="d" id="login">
    <div class="ui-content">
      <h1>Log in</h1>
      <p>Use your email and password to login.</p>
      <form action="login.php" method="post">
        <fieldset class="ui-field-contain">
          
          <div class="ui-field-contain no-field-separator">
            <label for="login_email">Email</label> <input id="login_email" name="login_email" placeholder="your@email.com" type="email">
          </div>
          
          <div class="ui-field-contain no-field-separator">
            <label for="login_pass">Password</label> <input id="login_pass" name="login_pass" type="password">
          </div>
          
          <div class="ui-field-contain">
            <btn type="submit" class="ui-btn ui-corner-all ui-btn-a">Log in</btn>
            <!-- 
            <div data-dismissible="false" data-overlay-theme="d" data-role="popup"
            data-theme="b" id="profilesuccess1" style="max-width:400px;">
              <div data-role="header" data-theme="a">
                <h1>Success</h1>
              </div>
              <div class="ui-content" role="main">
                <h3 class="ui-title">Your profile has been saved.</h3>
                <p>Now you can browse stores.</p><a class=
                "ui-btn ui-corner-all ui-btn-inline ui-btn-a" data-rel="back" href=
                "#">Okay</a> <a class="ui-btn ui-corner-all ui-btn-inline ui-btn-b"
                href="#browsestores">Browse Stores</a>
              </div>
            </div>
            -->
          </div>
        </fieldset>
      </form>
      <div>
        New here? <a data-ajax="false" class="ui-btn ui-corner-all ui-btn-b" href="register.php">Sign up</a>
      </div>
    </div>
  </section>