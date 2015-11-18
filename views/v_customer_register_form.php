<!-- This is the Sign up page -->
  <section data-role="page" data-theme="d" id="signup">
    <div class="ui-content">
      <h1>Sign up</h1>
      <p>Use your email and create a password to sign up.</p>
      
      <form action="register.php" method="post">
        
        <fieldset class="ui-field-contain">
          
          <div class="ui-field-contain no-field-separator">
            <label for="signup_email">Email</label> <input id="signup_email" name="signup_email" placeholder="your@email.com" type="email">
          </div>
          
          <div class="ui-field-contain no-field-separator">
            <label for="signup_pass">Password</label> <input id="signup_pass" name="signup_pass" type="password">
          </div>
          
          <div class="ui-field-contain">
            <input type="submit" value="Sign up" data-theme="b"/>
          </div>

        </fieldset>
      
      </form>
      
      <div>
        Have an account? <a data-ajax="false" class="ui-btn ui-corner-all" href="login.php">Log in</a>
      </div>

    </div><!--/.ui-content-->
  </section>