<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html { height: 100%; margin: 0; background-color: #212529; overflow: hidden; }
        #particles-js { position: absolute; width: 100%; height: 100%; z-index: 1; }
        .auth-wrapper { height: 100vh; display: flex; align-items: center; justify-content: center; position: relative; z-index: 2; }
        .auth-card { width: 100%; max-width: 450px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); opacity: 0; /* Hidden for Anime.js */ }
    </style>
</head>
<body>

    <div id="particles-js"></div>

    <div class="auth-wrapper container">
        <div class="card auth-card shadow-lg border-0 rounded-4 p-4">
            <div class="card-body">
                <h3 class="text-center mb-4 fw-bold">Create Account</h3>
                
                <?php if(isset($validation)): ?>
                    <div class="alert alert-danger py-2 fs-6">
                        <?= $validation->listErrors() ?>
                    </div>
                <?php endif; ?>

                <form action="/registerStore" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold">Full Name / Store Name</label>
                        <input type="text" name="name" class="form-control form-control-lg" value="<?= set_value('name') ?>" required placeholder="John Doe">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold">Email Address</label>
                        <input type="email" name="email" class="form-control form-control-lg" value="<?= set_value('email') ?>" required placeholder="name@example.com">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control form-control-lg" required placeholder="••••••••">
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted fw-semibold">I want to register as a:</label>
                        <select name="role" class="form-select form-select-lg">
                            <option value="customer" <?= set_select('role', 'customer', true) ?>>Customer (Buy Products)</option>
                            <option value="vendor" <?= set_select('role', 'vendor') ?>>Vendor (Sell Products)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 mb-3 shadow-sm">Complete Registration</button>
                </form>
                
                <div class="text-center">
                    <span class="text-muted">Already have an account?</span> 
                    <a href="/login" class="text-decoration-none fw-bold">Login here</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.2/anime.min.js"></script>

    <script>
        // 1. Initialize Particles.js
        particlesJS("particles-js", {
            "particles": {
                "number": { "value": 60, "density": { "enable": true, "value_area": 800 } },
                "color": { "value": "#ffffff" },
                "shape": { "type": "circle" },
                "opacity": { "value": 0.3 },
                "size": { "value": 3 },
                "line_linked": { "enable": true, "distance": 150, "color": "#ffffff", "opacity": 0.2, "width": 1 },
                "move": { "enable": true, "speed": 2 }
            },
            "interactivity": {
                "events": { "onhover": { "enable": true, "mode": "grab" } },
                "modes": { "grab": { "distance": 140, "line_linked": { "opacity": 0.5 } } }
            }
        });

        // 2. Initialize Anime.js
        anime({
            targets: '.auth-card',
            translateY: [-50, 0],
            opacity: [0, 1],
            duration: 1200,
            easing: 'easeOutElastic(1, .8)',
            delay: 300
        });
    </script>
</body>
</html>