$(document).ready(function() {
    var token = '';

    // URL API
    var loginUrl = 'http://127.0.0.1:8000/api/login';
    var dataUrl  = 'http://127.0.0.1:8000/api/admin/products';
    var ImageUrl = 'http://127.0.0.1:8000/storage/';

    // Fungsi untuk menangani kesalahan
    function handleError(jqXHR, textStatus, errorThrown) {
        console.error('Error: ' + textStatus, errorThrown);
        $('#dataResult').text('Error: ' + textStatus + ' ' + errorThrown);
    }

    // Login request
    $('#login').click(function() {
        var email = $('#email').val();
        var password = $('#password').val();
        var loginData = {
            email: email,
            password: password
        };

        $.ajax({
            url: loginUrl,
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(loginData),
            dataType: 'json',
            success: function(response) {
                token = response.data.token;
                $('#loginResult').text('Login successful! Token: ' + token);
            },
            error: handleError
        });
    });

    // Fungsi untuk membuat kartu produk
    function createCards(data) {
        var cards = $('<div class="card-deck"></div>');
        data.forEach(function(item) {
            var card = $('<div class="col-md-4 mb-4"></div>');
            var cardContent = $('<div class="product-card"></div>');
            var productImage = item.image ? '<img src="'+ ImageUrl + item.image + '" alt="Product Image" class="card-img-top">' : '';
            var productTitle = '<div class="product-title">' + item.nama_product + '</div>';
            var productDescription = '<p>' + item.description + '</p>';
            var productPrice = '<div class="product-price"><span class="old-price">$990.00</span> $' + item.price + '</div>';

            cardContent.append(productImage);
            cardContent.append('<div class="product-info">' + productTitle + productDescription + productPrice + '</div>');
            card.append(cardContent);
            cards.append(card);
        });

        return cards;
    }

    // Fetch data request
    $('#fetchData').click(function() {
        if (!token) {
            $('#dataResult').text('You must login first!');
            return;
        }

        $.ajax({
            url: dataUrl,
            type: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token
            },
            dataType: 'json',
            success: function(data) {
                $('#dataResult').empty(); // Kosongkan hasil sebelumnya
                
                if (data.success) {
                    $('#dataResult').append(createCards(data.data.products));
                } else {
                    $('#dataResult').text('Data fetched is not an array.');
                }
            },
            error: handleError
        });
    });
});
