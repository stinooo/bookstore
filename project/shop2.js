$(document).ready(function() {
    function search() {
        var searchproduct = $('#product-search').val();
        var selectedGenres = [];
        $('input[name="genreCheckbox"]:checked').each(function() {
            selectedGenres.push($(this).val());
        });
        $.ajax({
            url: './functions/Shop_Search_Products.php',
            type: 'POST',
            data: {
                search: searchproduct,
                genres: selectedGenres
            },
            success: function(response) {
                $('.books-grid').html(response);
            }
        });
    }

    $(document).on('keyup', '#product-search', function() {
        search();
    });

    $(document).on('change', 'input[name="genreCheckbox"]', function() {
        search();
    });

    $(document).on('click', '#Btn_orders', function(e) {
        e.preventDefault();
        console.log('Orders button clicked');
        $('#orders-popup').show();
        loadOrders();

    });
    $(document).on('click', '.close_orders', function(e) {
        e.preventDefault();
        $('#orders-popup').hide();
    });

    function loadOrders() {
        $.ajax({
            url: './functions/get_orders.php',
            type: 'POST',
            success: function(response) {
                $('#order-list').html(response);
            }
        });
    }

    $(document).on('click', '.fa-shopping-cart', function(e) {
        e.preventDefault();
        $('#cart-sidebar').addClass('active');
        CartItems();
        CartTotal();
    });

    $(document).on('click', '#close-cart', function() {
        $('#cart-sidebar').removeClass('active');
    });

    $(document).on('click', '.add-to-cart', function () {
        var productID = $(this).val();
        console.log('Product ID:', productID);
        $.ajax({
            url: './functions/add_to_cart.php',
            type: 'POST',
            data: {
                productID: productID,
            },
            success: function(response) {
                CartItems();
                CartTotal();
            }
        });
    });

    $(document).on('click', '.remove-from-cart', function() {
        var productID = $(this).val();
        console.log('Product ID:', productID);
        $.ajax({
            url: './functions/remove_from_cart.php',
            type: 'POST',
            data: {
                productID: productID
            },
            success: function(response) {
                CartItems();
                CartTotal();
            }
        });
    });

    $(document).on('click', '.plus-btn', function() {
        var productID = $(this).val();
        $.ajax({
            url: './functions/add_quantity.php',
            type: 'POST',
            data: {
                productID: productID
            },
            success: function(response) {
                CartItems();
                CartTotal();
            }
        });
    });

    $(document).on('click', '.minus-btn', function() {
        var productID = $(this).val();
        $.ajax({
            url: './functions/minus_quantity.php',
            type: 'POST',
            data: {
                productID: productID
            },
            success: function(response) {
                CartItems();
                CartTotal();
            }
        });
    });

    function CartItems() {
        $.ajax({
            url: './functions/get_cart_items.php',
            type: 'Post',
            success: function(response) {
                $('#cart-items').html(response);
            }
        });
    }

    function CartTotal() {
        $.ajax({
            url: './functions/get_cart_total.php',
            type: 'Post',
            success: function(response) {
                $('#cart-prijs').html(response);
            }
        });
    }
});