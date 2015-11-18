  <!-- This is the CUSTOMER profile edit page -->
  <section data-role="page" data-theme="d" id="customer_profile_edit">
    <div class="ui-content">
      <a class="smaller left" data-rel="back" href="#buysell">&lt; Back</a>
      <h1>Edit My Profile</h1>
      <p>Update your customer profile.</p>
      <form>
        <fieldset class="ui-field-contain">
        
          <div class="ui-field-contain no-field-separator">
            <label for="customer_name">Name</label> <input id="customer_name" name="customer_name"
            placeholder="Sam Anderson" type="text" value="">
          </div>
        
          <div class="ui-field-contain no-field-separator">
            <label for="customer_email">Email</label> <input id="customer_email" name=
            "customer_email" placeholder="sammyA@email.com" type="email" value="">
          </div>
        
          <div class="ui-field-contain no-field-separator">
            <label for="customer_phone">Phone</label> <input id="customer_phone" name=
            "customer_phone" placeholder="010-555-1234" type="number" value="">
          </div>
        
          <div class="ui-field-contain no-field-separator">
            <label for="customer_address">Shipping Address</label> 
            <textarea id="customer_address" name="customer_address" placeholder="123 Off Street, Seoul 123-123"></textarea>
          </div>

          <div class="gpslink">
            <a class=
            "smaller ui-nodisc-icon ui-btn ui-corner-all ui-btn-inline ui-btn-icon-left ui-icon-location"
            href="#customer_map">GPS location</a>
          </div>
          
          <div class="ui-field-contain">
            <input type="submit" value="Save" data-theme="b"/>
          </div>

        </fieldset>
      </form>
    </div>
    
    <footer data-position="fixed" data-role="footer">
      <div data-iconpos="bottom" data-role="navbar">
        <ul>
          <li><a data-icon="home" href="#browsestores">Browse Stores</a></li>
          <li><a data-icon="user" href="#customer_profile">Profile</a></li>
          <li><a data-icon="list-ul" href="#customer_orders">Orders</a></li>
          <li><a data-icon="exchange" href="#buysell">Buy/Sell</a></li>
        </ul>
      </div><!-- /navbar -->
    </footer><!-- /footer -->
  </section>