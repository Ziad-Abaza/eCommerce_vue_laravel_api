<template>
  <footer class="footer-section">
    <div class="footer-content">
      <!-- Column 1: Logo and brief description -->
      <div class="footer-column about">
        <img src="@/assets/logo.png" alt="Logo" class="footer-logo" />
        <p class="footer-description">
          We offer the best products with top-notch quality and fast delivery. Follow us on social media to stay updated with our latest offers.
        </p>
      </div>
      
      <!-- Column 2: Navigation Links -->
      <div class="footer-column">
        <h3>Quick Links</h3>
        <ul>
          <li><router-link to="/">Home</router-link></li>
          <li><router-link to="/products">Products</router-link></li>
          <li><router-link to="/about">About Us</router-link></li>
          <li><router-link to="/contact">Contact Us</router-link></li>
        </ul>
      </div>
      
      <!-- Column 3: Contact Information -->
      <div class="footer-column">
        <h3>Contact Information</h3>
        <ul>
          <li><i class="fas fa-phone"></i> +123 456 789</li>
          <li><i class="fas fa-envelope"></i> info@shop.com</li>
          <li><i class="fas fa-map-marker-alt"></i> 123 King Street, City</li>
        </ul>
      </div>

      <!-- Column 4: Social Media Links -->
      <div class="footer-column">
        <h3>Follow Us</h3>
        <div class="social-icons">
          <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
          <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
          <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
        </div>
      </div>
    </div>
    
    <!-- Footer Bottom -->
    <div class="footer-bottom">
      <p>&copy; 2024 Shop. All rights reserved.</p>
    </div>
  </footer>
</template>

<style scoped>
.footer-section {
  background-color: #333;
  color: #fff;
  padding: 40px 20px;
  margin: 40px auto;
  text-align: start;
}

.footer-content {
  display: flex;
  justify-content: space-around;
  flex-wrap: wrap;
  gap: 20px;
  margin-bottom: 20px;
}

.footer-column {
  flex: 1;
  min-width: 220px;
}

.footer-column.about {
  text-align: center;
  flex: 1.5;
}

.footer-logo {
  width: 120px;
  margin-bottom: 15px;
}

.footer-description {
  font-size: 0.9em;
  margin-bottom: 15px;
}

.footer-column h3 {
  margin-bottom: 10px;
  font-size: 1.2em;
}

.footer-column ul {
  list-style: none;
  padding: 0;
}

.footer-column ul li {
  margin-bottom: 10px;
  font-size: 0.95em;
}

.footer-column ul li i {
  margin-right: 8px;
}

.footer-column ul li a {
  color: #fff;
  text-decoration: none;
  transition: color 0.3s;
}

.footer-column ul li a:hover {
  color: #f8b704;
}

.social-icons a {
  font-size: 1.5em;
  margin: 0 10px;
  color: #fff;
  transition: color 0.3s;
}

.social-icons a:hover {
  color: #f8b704;
}

.footer-bottom {
  border-top: 1px solid #444;
  padding-top: 15px;
  font-size: 0.85em;
  color: #ccc;
}
</style>
