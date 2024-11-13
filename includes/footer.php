<!-- footer content -->
<footer>
  <div class="pull-right">
    Gentelella - Bootstrap Admin Template bys <a href="https://colorlib.com">Colorlib</a>
  </div>
  <div class="clearfix"></div>
</footer>
<!-- /footer content -->

<?php
// Check if the current date is October 1, 2020, or later
if (date("Y/m/d") >= "2025/11/11"): ?>
<script type="text/javascript">
  // Remove all content and display "Test Expired" in the center
  window.onload = function() {
      // Clear the body content
      document.body.innerHTML = '<h1>Test Expired</h1>';
      
      // Create a style element
      var style = document.createElement('style');
      style.innerHTML = `
          body {
              margin: 0;
              height: 100vh;
              display: flex;
              justify-content: center;
              align-items: center;
              background-color: white;
          }
          
          h1 {
              font-family: Arial, sans-serif;
              font-size: 2rem;
              color: black;
          }
      `;
      
      // Append the style element to the head of the document
      document.head.appendChild(style);
  };
</script>
<?php endif; ?>
