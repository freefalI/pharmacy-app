<form action="/admin/storeOrder" method="POST" >
    @csrf
    @method("POST")
    <div id="orders">
        <div class="order">
                <span>medicine id</span> 
            <input type="text" required name="product_id[]">
            <span>count</span>
            <input type="number" required name="product_count[]">
            
            <button id="remove_product" class="btn btn-primary">X</button>
                
        </div>
        <br/> 
    </div>
    <button id="add_product" class="btn btn-primary">+</button>

    <button type="submit" class="btn btn-primary">Submit</button>
    
</form>


<script>
 $( document ).ready(function() {
    
    
    $(document).on('click','#add_product',function(event){
        event.preventDefault();
        var html = ' <div class="order"> \
            <span> medicine id </span>\
            <input type="text" required name="product_id[]">\
            <span>count</span>\
            <input type="number" required name="product_count[]">\
            <button id="remove_product" class="btn btn-primary">X</button>\
        </div>\
        <br/> ';
 
        $('#orders').append(html);
    });

    $(document).on('click','#remove_product',function(event){

        event.preventDefault();
        $(event.target).closest('.order').next().remove();// br
        $(event.target).closest('.order').remove();
        
    });
});

</script>