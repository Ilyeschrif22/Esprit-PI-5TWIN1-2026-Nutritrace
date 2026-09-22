<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certifications</title>
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body>
    <!-- SideBar -->
    @include('components.sidebar')

    <div class="nutritrace-main-container">
        @include('components.navbar')

        <div class="nutritrace-content">
            <h1>Certifications</h1>
            <p>Page des certifications - Contenu à venir</p>
        </div>
    </div>

</body>

</html>
<script>
    (function () {
        var trigger = document.getElementById('profileTrigger');
        var dropdown = document.getElementById('profileDropdown');

        if (!trigger || !dropdown) return;

        trigger.addEventListener('click', function (e) {
            e.stopPropagation();
            trigger.classList.toggle('open');
        });

        document.addEventListener('click', function (e) {
            if (!trigger.contains(e.target)) {
                trigger.classList.remove('open');
            }
        });

        dropdown.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    })();
</script>
