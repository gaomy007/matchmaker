/*
UserCake Version: 2.0.2
http://usercake.com
*/
function showHide(div){
	if(document.getElementById(div).style.display = 'block'){
		document.getElementById(div).style.display = 'none';
	}else{
		document.getElementById(div).style.display = 'block'; 
	}
}

function disable(selected){
    if(selected==0){
        document.getElementById("score").style.display="none";
    }
    else
        {
         document.getElementById("score").style.display="block";
            
            
        }
    
    
}

   var customIcons = {
      restaurant: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png'
      },
      bar: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_red.png'
      }
    };
    
    
    
         function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width(140)
                        .height(140);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

