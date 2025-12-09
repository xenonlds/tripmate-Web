<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register Your Business</title>
 
  @vite('resources/css/form.css')
</head>
<body>
  <div class="container">
    <h2>Register Your Business</h2>
    <p>Fill out the form below to register your hotel or restaurant with TripMate</p>

    <form class="form" 
          action="{{ url('guest/apply/store') }}" 
          method="POST" 
         >
       @csrf
      <!-- Business Type -->
      <div class="section">
        <label class="section-title">Business Type</label>
        <div class="radio-group">
          <label><input type="radio" name="business_type" value="Hotel" checked /> Hotel</label>
          <label><input type="radio" name="business_type" value="Restaurant" /> Restaurant</label>
        </div>
      </div>

      <!-- Business Info -->
      <div class="two-columns">
        <div class="form-group">
          <label>Business Name</label>
          <input type="text" name="business_name" placeholder="Enter your business name" required />
        </div>
        <!-- <div class="form-group">
          <label>Business License</label>
          <select name="business_license" required>
            <option value="">Select license type</option>
            <option value="Tourism License">Hotel License</option>
            <option value="Restaurant License">Restaurant License</option>
            <option value="Food Handling Permit">Food Handling Permit</option>
          </select>
        </div> -->
        <div class="form-group">
          <label>Business License No.</label>
          <input type="text" name="business_license_no" placeholder="Enter your business license number" required />
        </div>
      </div>

      <!-- Contact Info -->
      <h4>Contact Information</h4>
      <div class="two-columns">
        <div class="form-group">
          <label>Owner/Manager Name</label>
          <input type="text" name="owner_name" placeholder="Full name" required />
        </div>
        <div class="form-group">
          <label>Email Address</label>
          <input type="email" name="email" placeholder="business@example.com" required />
        </div>
      </div>

      <div class="two-columns">
        <div class="form-group">
          <label>Phone Number</label>
          <input type="tel" name="phone" placeholder="+60 12-345 6789" required />
        </div>
        
      </div>

      <!-- Location -->
      <h4>Location Details</h4>
      <div class="form-group">
        <label>Full Address</label>
        <textarea name="address" placeholder="Enter complete business address" required></textarea>
      </div>

      <div class="three-columns">
        <div class="form-group">
          <label>City</label>
          <input type="text" name="city" placeholder="City" required />
        </div>
        <div class="form-group">
          <label>State/Province</label>
          <input type="text" name="state" placeholder="State" required />
        </div>
        <div class="form-group">
          <label>ZIP/Postal Code</label>
          <input type="text" name="zip" placeholder="ZIP Code" required />
        </div>
      </div>

       <div class="three-columns">
        <div class="form-group">
          <label>Country</label>
          <input type="text" name="country" placeholder="Country" required />
        </div>
     
      </div>

      <!-- Business Details -->
      <h4>Business Details</h4>
      <div class="form-group">
        <label>Business Description</label>
        <textarea name="description" placeholder="Describe your business, amenities, and unique features..." required></textarea>
      </div>

      <!-- Upload Section -->
      <!-- <h4>Upload Required Documents</h4>

      <div class="form-group">
        <label>Business Registration Certificate (PDF or Image)</label>
        <input type="file" name="registration_cert" accept=".pdf,.jpg,.jpeg,.png" required />
      </div>

      <div class="form-group">
        <label>Operating License</label>
        <input type="file" name="license_doc" accept=".pdf,.jpg,.jpeg,.png" required />
      </div>

      <div class="form-group">
        <label>Owner/Manager ID Proof</label>
        <input type="file" name="id_proof" accept=".pdf,.jpg,.jpeg,.png" required />
      </div>

      <div class="form-group">
        <label>Tax or SST Certificate (Optional)</label>
        <input type="file" name="tax_doc" accept=".pdf,.jpg,.jpeg,.png" />
      </div> -->

      <button type="submit" class="submit-btn">Submit Registration</button>
    </form>
  </div>
</body>
</html>
