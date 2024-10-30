// document.addEventListener("DOMContentLoaded", function(){
//     window.addEventListener('scroll', function() {
//         if (window.scrollY > 200) {
//             document.getElementById('acq-header').classList.add('fixed-top');
//             navbar_height = document.querySelector('.acqnav-mnu').offsetHeight;
//             document.body.style.paddingTop = navbar_height + 'px';
//         } else {
//             document.getElementById('acq-header').classList.remove('fixed-top');
//             document.body.style.paddingTop = '0';
//         } 
//     });
// });


// let lastScrollTop = 0;
// const navbar = document.getElementById("acq-header");
// window.addEventListener("scroll", function() {
//   let currentScroll = window.pageYOffset || document.documentElement.scrollTop;
//   if (currentScroll < lastScrollTop) {
//     // User is scrolling up
//     navbar.classList.add("fixed-top");
//   } else {
//     // User is scrolling down
//     navbar.classList.remove("fixed-top");
//   }
//   lastScrollTop = currentScroll <= 0 ? 0 : currentScroll; // Avoid negative values
// });

var $menuTrigger = jQuery(".js-menuToggle");
var $topNav = jQuery(".js-topPushNav");
var $openLevel = jQuery(".js-openLevel");
var $closeLevel = jQuery(".js-closeLevel");
var $closeLevelTop = jQuery(".js-closeLevelTop");
var $navLevel = jQuery(".js-pushNavLevel");

function openPushNav() {
    $topNav.addClass("isOpen");
    jQuery("html").addClass("pushNavIsOpen");
}

function closePushNav() {
    $topNav.removeClass("isOpen");
    $openLevel.siblings().removeClass("isOpen");
    jQuery("html").removeClass("pushNavIsOpen");
}

$menuTrigger.on("click", function (e) {
    e.preventDefault();
    if ($topNav.hasClass("isOpen")) {
        closePushNav();
    } else {
        openPushNav();
    }
});

$openLevel.on("click", function () {
    jQuery(this).next($navLevel).addClass("isOpen");
});

$closeLevel.on("click", function () {
    jQuery(this).closest($navLevel).removeClass("isOpen");
});

$closeLevelTop.on("click", function () {
    closePushNav();
});

jQuery(".screen").click(function () {
    closePushNav();
});

jQuery(document).ready(function () {
    function setOpacityOnHover(primaryClass, secondaryClass) {
        jQuery(primaryClass).mouseenter(function () {
            // Change opacity of other elements with the primary class to 0.5
            jQuery(primaryClass).not(this).css('opacity', '0.5');
            // Change opacity of elements with the secondary class to 0.5
            jQuery(secondaryClass).css('opacity', '0.5');
        }).mouseleave(function () {
            // Reset opacity of all elements with the primary class to 1
            jQuery(primaryClass).css('opacity', '1');
            // Reset opacity of all elements with the secondary class to 1
            jQuery(secondaryClass).css('opacity', '1');
        });
    }

    // Apply the function to both combinations
    setOpacityOnHover('.acqtp_nvlink', '.acqmnu_nvlink');
    setOpacityOnHover('.acqmnu_nvlink', '.acqtp_nvlink');
});



// JavaScript to handle open and close of dropdown

document.addEventListener('DOMContentLoaded', function() {
   const menuLink = document.getElementById('sfmob-menu-link');
   const dropdownMenu = document.getElementById('sfmop-dropdown');
   const parentLi = menuLink.parentElement;
   const arrowIcon = document.getElementById('salesforce-arrow');

   menuLink.addEventListener('click', function(e) {
       e.preventDefault(); // Prevent the default anchor link behavior
       
       // Toggle the open class on the parent <li> element
       if (parentLi.classList.contains('open')) {
           parentLi.classList.remove('open');
           // Change icon to down arrow
           arrowIcon.classList.remove('fa-chevron-up');
           arrowIcon.classList.add('fa-chevron-down');
       } else {
           parentLi.classList.add('open');
           // Change icon to up arrow
           arrowIcon.classList.remove('fa-chevron-down');
           arrowIcon.classList.add('fa-chevron-up');
       }
   });

   // Close the dropdown if clicked outside
   document.addEventListener('click', function(event) {
       if (!parentLi.contains(event.target)) {
           parentLi.classList.remove('open');
           // Reset arrow to down if the dropdown is closed
           arrowIcon.classList.remove('fa-chevron-up');
           arrowIcon.classList.add('fa-chevron-down');
       }
   });
});