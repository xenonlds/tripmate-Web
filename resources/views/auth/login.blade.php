<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TripMate Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  @vite('resources/css/login.css')
</head>

<body>
  <div class="logo-container">
    <img src="{{ asset('image/tripmate.png') }}" alt="TripMate logo" width="72" height="72">
    <p class="subtitle">Management System</p>
  </div>

  <main>
    <section class="login-box" aria-label="Sign in to TripMate hotel management system">
      <h2>Welcome Back</h2>
      <p class="description">Sign in to your business management dashboard</p>

      <form id="login-form">
        @csrf
        <label for="email">Email</label>
        <input id="email" name="email" type="email" placeholder="Enter your email" required />

        <label for="password" style="margin-top:16px;">Password</label>
        <input id="password" name="password" type="password" placeholder="Enter your password" required />

        <button type="submit">Sign In</button>
      </form>

      <a class="forgot-password" href="#">Forgot your password?</a>
    </section>
  </main>

  <p class="bottom-text">
    Don't have an account?
    <a href="#">Apply Now!!</a>
  </p>

  <script>
    document.getElementById("login-form").addEventListener("submit", async (e) => {
      e.preventDefault();

      const email = document.getElementById("email").value;
      const password = document.getElementById("password").value;
      const button = document.querySelector("button[type='submit']");

      let loadingOverlay = document.createElement("div");
      loadingOverlay.style.position = "fixed";
      loadingOverlay.style.top = 0;
      loadingOverlay.style.left = 0;
      loadingOverlay.style.width = "100%";
      loadingOverlay.style.height = "100%";
      loadingOverlay.style.background = "rgba(255,255,255,0.7)";
      loadingOverlay.style.display = "flex";
      loadingOverlay.style.justifyContent = "center";
      loadingOverlay.style.alignItems = "center";
      loadingOverlay.style.zIndex = "9999";

      const loadingImg = document.createElement("img");
      loadingImg.src = "{{ asset('image/Loading.gif') }}";
      loadingImg.alt = "Loading...";
      loadingImg.style.width = "80px";
      loadingImg.style.height = "80px";
      loadingOverlay.appendChild(loadingImg);

      button.disabled = true;
      button.textContent = "Signing In...";
      document.body.appendChild(loadingOverlay);

      try {
        const response = await fetch("https://tripmate-service-3.onrender.com/api/user/login", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify({
            email,
            password
          }),
        });

        const data = await response.json();

        if (data.success) {
          await fetch("{{ route('setSession') }}", {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
              "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: `user_id=${data.user_id}&role=${data.role}&name=${data.name}`
          });

          if (data.role === "staff") window.location.href = "{{ url('staff/dashboard') }}";
          else if (data.role === "owner") window.location.href = "{{ url('business_owner/dashboards') }}";
          else if (data.role === "admin") window.location.href = "{{ url('admin/dashboards') }}";
          else window.location.href = "{{ url('tourist/home') }}";
        } else {
          alert(data.message);
        }

      } catch (error) {
        alert("Network error, please try again later.");
        console.error(error);
      } finally {
        button.disabled = false;
        button.textContent = "Sign In";
        if (loadingOverlay && loadingOverlay.parentNode) {
          loadingOverlay.remove();
        }
      }
    });
  </script>

</body>

</html>
