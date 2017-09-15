$(function () {
    $('.navbar-toggle').click(function () {
        $('.navbar-nav').toggleClass('slide-in');
        $('.side-body').toggleClass('body-slide-in');
        $('#search').removeClass('in').addClass('collapse').slideUp(200);

        /// uncomment code for absolute positioning tweek see top comment in css
        //$('.absolute-wrapper').toggleClass('slide-in');
        
    });
   
   // Remove menu for searching
   $('#search-trigger').click(function () {
        $('.navbar-nav').removeClass('slide-in');
        $('.side-body').removeClass('body-slide-in');

        /// uncomment code for absolute positioning tweek see top comment in css
        //$('.absolute-wrapper').removeClass('slide-in');

    });
});

function image_preview() {
  var preview = $(".upload-preview img");

  $(".file").change(function(event){
     var input = $(event.currentTarget);
     var file = input[0].files[0];
     var reader = new FileReader();
     reader.onload = function(e){
         image_base64 = e.target.result;
         preview.attr("src", image_base64);
     };
     reader.readAsDataURL(file);
  });
}

$(document).ready(image_preview);
$(document).on('page:load', image_preview);
$(function(){

    $('#example').DataTable( {
        "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
    } );
});

$(document).ready(function(){
    function load_district()
    {
        var province_id = $('#province option:selected').val();
        $.ajax({
            type: 'POST',
            url: '/loaddistrict',
            data: {
                'province_id' : province_id,
            },
            success: function(data) {
                var data = JSON.parse(data);
                $('#district').find('option').remove();
                for (var k in data){
                    $('#district').append('<option value="' + k + '">' + data[k] + '</option>');
                }                               
            }
        })
    }


    $('#province').on("change", function(){
        load_district();
    });
});
