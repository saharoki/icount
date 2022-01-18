let network = null;

$(document).ready(function() {
  $('#country').on('change', function(){
      $('#network').html('<option value="0">Choose Network</option>');
      $('#data').html('');
      network = null;
      let country_code = $(this).val();
      if(country_code == 0){
          return;
      }
      let country = $('#country option:selected').text();
      let link = $('#country option:selected').data('link');
      $.ajax({
          type: "get",
          url: 'api/network.php',
          data: {
              "country": country,
              "link": link
          },
          success: function(response, status)
          {
              network = JSON.parse(response);
              let option = '<option value="0">Choose Network</option>';
              $.each(network, function (k, v) {
                  option +='<option value="'+ v.mnc+ '">'+ v.operator +'</option>'; 
              });
              $('#network').html(option);
          },
          error: function(data, status)
          {
              alert('Something went wrong. Try Again later')
          }
      });
  });
  
  $('#network').on('change', function(){
      let val = $(this).val();
      if (val == '0'){
          $('#data').html('');
          return;
      }
      let data = network.find(obj => {return obj.mnc == $(this).val()});
      let row ='<tr><td>'+ $('#country').val() +'</td><td>'+ $('#country option:selected').text() +'</td>';
      row +='<td>'+ data.mcc +'</td><td>'+ data.mnc +'</td><td>'+ data.brand +'</td><td>'+ data.operator +'</td>';
      row +='<td>'+ data.status +'</td><td>'+ data.bands +'</td></tr>';
      $('#data').html(row);
  })
})