const profileButton = document.querySelector('.nutritrace-profile-button');
const profile = document.querySelector('.nutritrace-profile');

profileButton.addEventListener('click', function (e) {
    e.stopPropagation();
    profile.classList.toggle('active');
});

document.addEventListener('click', function () {
    profile.classList.remove('active');
});
