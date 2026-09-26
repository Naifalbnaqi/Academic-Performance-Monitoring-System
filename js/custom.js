// $(document).ready(function () {
// $('.heblock').click(function(){
//    $('.hide-block').fadeToggle();
// });
// });


$(document).ready(function () {
    $('.heblock').click(function () {
        $(this).siblings(".hide-block").fadeToggle();
    });
});
