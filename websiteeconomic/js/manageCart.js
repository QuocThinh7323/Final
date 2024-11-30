$(document).ready(function(){
    addProductToCart();
})

function addProductToCart(productId){
    e.preventDefault();

    const productCart = document.querySelector('.add-to-cart-'+productId);
    var pid = $(this).data('id');
    var qty = $(this).data('qty');
    $.ajax({
        type: 'POST',
        // url: '<?=$_SERVER['REQUEST_URI']?>',
        data: {
            addtocart: true,
            pid: pid,
            qty: qty
        },
        success: function(response) {
            // Thông báo SweetAlert khi thêm sản phẩm thành công
            Swal.fire({
                title: 'Success!',
                text: 'The product has been added to the cart!',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        },
        error: function() {
            // Thông báo SweetAlert khi có lỗi
            Swal.fire({ 
                title: 'Error!',
                text: 'An error has occurred. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });

}