/**
 * sticky
 */
// When the user scrolls the page, execute myFunction
window.onscroll = function() {myFunction()};

// Get the header
var header = document.getElementById("header-logo");

// Get the offset position of the navbar
var sticky = header.offsetTop;

// Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position
function myFunction() {
    if (window.pageYOffset >= sticky) {

        header.classList.add("sticky");
    } else {
        header.classList.remove("sticky");
    }
}

$(document).on('click', '#create-account', function (e) {
   if($(this).prop('checked')) {
       $('.create-account').show();
       $('#nb_licences_a_creer').attr('required',true);
       $('#volume_par_licence_Go').attr('required',true);
   } else {
       $('.create-account').hide();
       $('#nb_licences_a_creer').attr('required',false);
       $('#volume_par_licence_Go').attr('required',false);
   }
});