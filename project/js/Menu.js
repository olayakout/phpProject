var count=0;
var array=[];
var total_price=0;
var parameters=0;

function info(array_parameters)
{
  parameters=array_parameters.split(",");
  //console.log(array_parameters[2]);
  count+=1;
  //console.log("after new press"+String(count));
  var div=document.createElement('div');
      div.setAttribute("class","div_note");
      div.setAttribute("id",'div_notes');

  var notes = document.createElement('label');
      notes.setAttribute("type", "label");
      notes.setAttribute("class","notes");
      notes.innerHTML="Notes";

  var notes_text=document.createElement('textarea');
      notes_text.setAttribute("class","notes_text");
      notes_text.setAttribute("name","notes_text");


  var div_room=document.createElement('div');
      div_room.setAttribute("class","div_room");
      div_room.setAttribute("id",'div_room');

  var room = document.createElement('label');
      room.setAttribute("type", "label");
      room.setAttribute("class","room");
      room.innerHTML="Room No.";

  var room_text=document.createElement('input');
      room_text.setAttribute("class","room_no");  
      room_text.setAttribute("id","room_no"); 
      room_text.setAttribute("type","text");  
      room_text.setAttribute("name","room");
      room_text.setAttribute("required","required");
      room_text.setAttribute("pattern","[a-zA-Z0-9]+");

  var myroom=document.createElement('button');    
      myroom.setAttribute("type", "button");
      myroom.setAttribute("class","myroom");
      myroom.setAttribute("id","myroom");
      myroom.innerHTML="My Room";
      myroom.setAttribute("onClick","setRoomNo();");

  var divider=document.createElement('hr');
      divider.setAttribute("id","divider");
      divider.setAttribute("class","divider");

  var div_totalPrice=document.createElement('div');
      div_totalPrice.setAttribute("class","div_totalPrice");
      div_totalPrice.setAttribute("id",'div_totalPrice');

  var totalPrice = document.createElement('label');
      totalPrice.setAttribute("type", "label");
      totalPrice.setAttribute("class","totalPrice");
      totalPrice.innerHTML="EPG.";

  var totalPrice_text=document.createElement('label');
      totalPrice_text.setAttribute("class","total");   
      totalPrice_text.setAttribute("id","total");

  var confirm=document.createElement('input');
    confirm.setAttribute("type","submit");
    confirm.setAttribute("id","confirm");
    confirm.setAttribute("class","confirm");   
    confirm.setAttribute("value","Confirm");
    confirm.setAttribute("disabled","disabled");
    confirm.setAttribute("style","opacity:0.5");
   
      var box=document.getElementById("box");

  if(count==1){
    var welcome = document.getElementById('welcome');
    welcome.style.display="none";
    div.appendChild(notes);
    div.appendChild(notes_text);

    div_totalPrice.appendChild(totalPrice);
    div_totalPrice.appendChild(totalPrice_text);

    div_room.appendChild(room);
    div_room.appendChild(room_text);
    div_room.appendChild(myroom);

    box.appendChild(div);
    box.appendChild(div_room);
    box.appendChild(divider);
    box.appendChild(div_totalPrice);
    box.appendChild(confirm);


   $(document).ready(function(){
      $('#room_no').change(function(){

        $('#confirm').removeAttr('disabled');
        $('#confirm').removeAttr('style');

      });


    });


  }

  //alert(array_parameters);
  array_parameters = array_parameters.split(",");
  if(array.indexOf(array_parameters[0])==-1){
      array.push(array_parameters[0]);
      var table = document.getElementById('order');
      var tr = document.createElement('tr');
      tr.setAttribute("id","tr"+array_parameters[0]);
      var td1= document.createElement('td');
      td1.setAttribute("class", "td_order");
      var td2= document.createElement('td');
      var td3= document.createElement('td');
      td3.setAttribute("class", "td_price");
      var td4= document.createElement('td');
      td4.setAttribute("class", "td_cancel");      

      
      var input = document.createElement('input');
      input.setAttribute("type", "number");
      input.setAttribute("value", "1");
      input.setAttribute("min","1");
      input.setAttribute("class", "input_no");
      input.setAttribute("id",array_parameters[0]);
      input.setAttribute("name",array_parameters[0]);

      var text = document.createTextNode(array_parameters[0]);
      var label=document.createElement('label');
      label.setAttribute("id","price"+array_parameters[0]);
      var price= document.createTextNode(array_parameters[1]);
      var LE= document.createTextNode(" L.E");
      total_price+=Number(array_parameters[1]);
      //console.log(total_price);
      
      var total=document.getElementById("total");
      total.innerHTML=total_price;

      var button = document.createElement('button');
      button.setAttribute("type", "button");
      button.setAttribute("class","cancel");
      button.setAttribute("id","cancel_"+array_parameters[0]);
      button.innerHTML="X";
      button.setAttribute("onClick","cancel(this.id);");


      td1.appendChild(text);
      td2.appendChild(input);
      td3.appendChild(label);
      td3.appendChild(LE);
      tr.appendChild(td1);
      tr.appendChild(td2);
      tr.appendChild(td3);
      tr.appendChild(td4);
      table.appendChild(tr);
      label.appendChild(price);
      td4.appendChild(button);

}
else{
  count-=1;
  var input = document.getElementById(array_parameters[0]);
  var no=Number(input.value);
  //console.log("oldvalue="+input.value);
  input.value=Number(input.value)+1;
  var price = document.getElementById("price"+array_parameters[0]).childNodes[0];
  
  //console.log(price.nodeValue);
  price.nodeValue=Number(input.value)*Number(array_parameters[1]);
  

  total_price=Number(total_price)+Number(array_parameters[1]);
  //console.log(total_price);

  var total=document.getElementById("total");
      total.innerHTML=total_price;



}

$(document).ready(function(){

    // assign oldVal data attribute at once on document ready
    $(".input_no:input").data('oldVal', $(".input_no:input").val());

     $(".input_no:input").change(function(){

            var newVal = $(this).val();
            var oldVal = $(this).data('oldVal');

            if ( newVal > oldVal) { //UP arrow pressed
                
                var no=Number(input.value);
                //console.log(no);
                var price = document.getElementById("price"+array_parameters[0]).childNodes[0];
                //console.log(price);
                price.nodeValue=Number(input.value)*Number(array_parameters[1]);

                total_price=Number(total_price)+Number(array_parameters[1]);
                console.log(total_price);
                    var total=document.getElementById("total");
                        total.innerHTML=total_price;

            } else if(  newVal < oldVal){  //down arrow
                var no=Number(input.value);
                var price = document.getElementById("price"+array_parameters[0]).childNodes[0];
                
                price.nodeValue=Number(input.value)*Number(array_parameters[1]);

                  if(no!=1 || (oldVal==2 && newVal==1)){
                  total_price=Number(total_price)-Number(array_parameters[1]);

                  var total=document.getElementById("total");
                      total.innerHTML=total_price;
                  }

            }
            console.log( 'newVal is ' + newVal + ' oldVal was ' + oldVal);
            //store the newVal as the oldVal for the next change
            $(this).data('oldVal', newVal)
       })

});


}

function cancel(clicked_id){
  id=clicked_id.split("_");

  var tr=document.getElementById("tr"+id[1]);
  var index=array.indexOf(id[1]);
  var div=document.getElementById("div_notes");
  var div_room=document.getElementById("div_room");
  var div_totalPrice=document.getElementById("div_totalPrice");
  var divider=document.getElementById("divider");
  var confirm=document.getElementById("confirm");

  var price=document.getElementById("price"+id[1]).childNodes[0];
  //console.log("cancel price"+String(price.nodeValue));

  var total=document.getElementById("total");
  total_price=total_price-Number(price.nodeValue);
      total.innerHTML=total_price;

  //console.log(div);

  array.splice(index,1);
  count-=1;
  //console.log(array);
  
  tr.remove();
  //console.log("after cancel order"+String(count));
  if(count==0&&array.length==0){
    var welcome = document.getElementById('welcome');
    welcome.style.display="block";
    div.remove();
    div_room.remove();
    divider.remove();
    div_totalPrice.remove();
    confirm.remove();

    //console.log("display welcome and remove notes");
  }

}

function setRoomNo(){
  var room_no=document.getElementById("room_no");
      room_no.setAttribute("value",parameters[2]);

  var confirm=document.getElementById("confirm");
      confirm.removeAttribute("disabled");
      confirm.removeAttribute("style");

}