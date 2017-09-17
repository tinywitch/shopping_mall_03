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

// show image primary
function image_preview() {
  var preview = $(".upload-image img");

  $("#image").change(function(event){
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
var i = 0;
function add_new_field() {
    var html = '<br><hr /><div class="row"><div class="col-md-6"><div class="form-group"><label for="image[]">Image</label><input type="file" name="image[]" class="file form-control width-custom" id="image[]"></div><div class="form-group"><label for="color[]">Color :</label>                            <select name="color[]" class="form-control width-custom" id="color"><option value="1">White</option><option value="2">Black</option><option value="3">Blue</option><option value="4">Yellow</option><option value="5">Red</option><option value="6">Green</option><option value="7">Purple</option><option value="8">Orange</option><option value="9">Light blue</option><option value="10">Sky blue</option><option value="11">Grey</option></select>'
    //var size = '<div class="form-group"> <label for="size[]">Size : </label> <label><input type="checkbox" name="size[][]" value="1">21</label><label><input type="checkbox" name="size[][]" value="2">22</label><label><input type="checkbox" name="size[][]" value="3">23</label><label><input type="checkbox" name="size[][]" value="4">24</label><label><input type="checkbox" name="size[][]" value="5">25</label><label><input type="checkbox" name="size[][]" value="6">26</label><label><input type="checkbox" name="size[][]" value="7">27</label><label><input type="checkbox" name="size[][]" value="8">28</label><label><input type="checkbox" name="size[][]" value="9">29</label><label><input type="checkbox" name="size[][]" value="10">30</label><label><input type="checkbox" name="size[][]" value="11">31</label></div>' ;
    var details = '</div></div><div class="col-md-1"></div><div class="col-md-5"><div class="form-group "><label for="imageDetail1[]">Image details</label>                                    <input type="file" name="imageDetail1[]" class="file form-control width-150"></div><div class="form-group "><label for="imageDetail2[]">Image detail2</label><input type="file" name="imageDetail2[]" class="file form-control width-150"></div><div class="form-group "><label for="imageDetail3[]">Image detail3</label><input type="file" name="imageDetail3[]" class="file form-control width-150"></div><div class="form-group "><label for="imageDetail4[]">Image detail4</label><input type="file" name="imageDetail4[]" class="file form-control width-150"></div></div>';  
    html = html  + details;
    $("#add-new-field").click(function(){
        
    $("#field-color").append(html);
    });
}
$(document).ready(add_new_field);

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

    
$(document).ready(function(){
    $('#date-start').datetimepicker({
        format: 'DD/MM/YYYY'
    });
    $('#date-end').datetimepicker({
        format: 'DD/MM/YYYY'
    });

    $('#1-day').on("click", function(){
      var date = $('#date-start').val();

      //change date format to YYYYMMDD
      var parts = date.split('/');
      var date_start = new Date(parts[2], parts[1]-1, parts[0]);

      var day = 60 * 60 * 24 * 1000; 
      var date_end = new Date(date_start.getTime() + day);
      $('#date-end').data("DateTimePicker").date(date_end);
    })
    $('#1-week').on("click", function(){
      var date = $('#date-start').val();
      
      //change date format to YYYYMMDD
      var parts = date.split('/');
      var date_start = new Date(parts[2], parts[1]-1, parts[0]);

      var days = 7 * 60 * 60 * 24 * 1000; 
      var date_end = new Date(date_start.getTime() + days);
      $('#date-end').data("DateTimePicker").date(date_end);
    })
    $('#1-month').on("click", function(){
      var date = $('#date-start').val();

      //change date format to YYYYMMDD
      var parts = date.split('/');
      var date_start = new Date(parts[2], parts[1]-1, parts[0]); 

      var days = 30 * 60 * 60 * 24 * 1000; 
      var date_end = new Date(date_start.getTime() + days);
      $('#date-end').data("DateTimePicker").date(date_end);
    })
})
