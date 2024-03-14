jQuery(document).ready(function ($)
{
    /***********************
     *           Letters
     ***********************/


    $.fn.lettering = function (method)
    {
        return this.each(function ()
        {
            let letters = $(this).text().split('');
            $(this).css({visibility: 'hidden', overflow: 'hidden', position: 'relative'});
            $(this).width($(this).width());
            $(this).height($(this).height());

            let html = '';
            for (var i = 0; i < letters.length; i++)
            {
                html += '<span>' + letters[i] + '</span>';
            }
            $(this).html(html);

            $(this).find('span').each(function (i, e)
            {
                $(this).css({
                    top: $(this).get(0).offsetTop,
                    left: $(this).get(0).offsetLeft,
                    animationDelay: i * 0.1 + 's'
                });
            });

            $(this).find('span').addClass('loaded');

            $(this).css({visibility: 'visible'});
        });
    };

    // $(window).load(function()
    $(document).ready(function()
    {
        $('.single-memory-name').lettering();
    });

    /**************************
     *           Fight
     ***************************/

    $('.single-memory-fight__section canvas').each(function ()
    {
        let canvasWidth = $(this).width();
        let canvasHeight = $(this).height();
        let colors = ['#000000', '#4c4636', '#2a2519', '#3b3629', '#433e2f', '#4c4636'];
        const ctx = $(this).get(0).getContext("2d");
        ctx.canvas.width = canvasWidth;
        ctx.canvas.height = canvasHeight;

        for (let x = 0; x < canvasWidth; x += 30)
        {
            for (let y = 0; y < canvasHeight; y += 30)
            {
                ctx.fillStyle = colors[Math.floor(Math.random() * 5)];
                if (Math.floor(Math.random() * 3) == 2)
                {
                    ctx.fillRect(x, y, 30, 30);
                }
            }
        }
    });

    $(window).on('scroll', function ()
    {
        let scrollPos = $(window).scrollTop();
        let containerTop = $('.single-memory-fight').offset().top;
        let height = $('.single-memory-fight').height();
        let percentage = Math.min(height, Math.max(0, scrollPos - containerTop)) / height;
        if (percentage < 0.25)
        {
            $('.single-memory-fight__section--over1').css('opacity', percentage / 0.25);
        }
        else if (percentage < 0.5)
        {
            $('.single-memory-fight__section--over2').css('opacity', (percentage - 0.25) / 0.25);
        }
        else
        {
            $('.single-memory-fight__section--over3').css('opacity', (percentage - 0.5) / 0.25);
        }
    });

    /***************************
     *               MAP
     ***************************/
    if (typeof mapboxgl !== 'undefined' && typeof MapboxGeocoder !== 'undefined') {

        $('.mapbox').each(function () {
            let fightAdress = $(this).attr('data-location');
            mapboxgl.accessToken = $(this).attr('data-key');

            let map = new mapboxgl.Map({
                container: $(this).attr('id'),
                center: [$(this).attr('data-long'), $(this).attr('data-lat')],
                zoom: 17,
                attributionControl: false,
                style: 'mapbox://styles/alex-anthracite/clssv42xu00wk01qx2r2gbmpr',
                hash: true,
            });

            let geocoder = new MapboxGeocoder({
                accessToken: mapboxgl.accessToken,
                mapboxgl: mapboxgl,
                marker: false,
                showSearch: false,
            });

            map.addControl(geocoder);

            geocoder.on('result', function (e) {
                let coordinates = e.result.center;

                map.flyTo({
                    center: coordinates,
                    zoom: 12,
                });
            });
            // geocoder.query(fightAdress);
            if (fightAdress && typeof fightAdress === 'string') {
                geocoder.query(fightAdress);
            }

            if ($(this).attr('data-region')) {

                map.on("load", function () {


                    // Add a vector source for admin-1 boundaries
                    map.addSource("admin-1", {
                        type: "vector",
                        url: "mapbox://mapbox.boundaries-adm1-v4",
                        promoteId: "mapbox_id"
                    });

                    // Define a filter for US worldview boundaries
                    let worldviewFilter = [
                        "any",
                        ["==", "all", ["get", "worldview"]],
                        ["in", "UA", ["get", "worldview"]]
                    ];

                    // Add a style layer with the admin-1 source below map labels
                    map.addLayer(
                        {
                            id: "admin-1-fill",
                            type: "fill",
                            source: "admin-1",
                            "source-layer": "boundaries_admin_1",
                            filter: worldviewFilter,
                            paint: {
                                "fill-color": "#CCCCCC",
                                "fill-opacity": 0.5
                            }
                        },
                        // This final argument indicates that we want to add the Boundaries layer
                        // before the `waterway-label` layer that is in the map from the Mapbox
                        // Light style. This ensures the admin polygons are rendered below any labels
                        "waterway-label"
                    );
                });
            }
        });
    };

    /*************************
     *  Rewards Cards - read-more
     ***********************/

    $('.single-memory-rewards__modal__close').click(function ()
    {
        $(this).closest('.single-memory-rewards__modal').addClass('hidden');
    });

    $('.single-memory-rewards__items__more-btn').click(function ()
    {
        $('.single-memory-rewards__items-row.hidden:first').removeClass('hidden');
    });

    $('.single-memory-rewards__item-description').each(function () {
        var $description = $(this);
        if ($description.height() > 140) {
            $description.siblings('.single-memory-rewards__item-more').show();
            $description.height(144);
        }
    });

    $('.single-memory-rewards__item').click(function () {
        var $description = $(this).find('.single-memory-rewards__item-description');
        if ($description.height() > 140) {
            var dataId = $(this).attr('data-id');
            $('.single-memory-rewards__modal[data-id="' + dataId + '"]').removeClass('hidden');
        }
    });
    /***********************
     *  Dynamic Pixel Canvas
     *********************/
    $('.single-memory-top').on('mousemove', function (event)
    {
        var mouseX = event.clientX;
        var mouseY = event.clientY;

        $('.single-memory-top__bg path').each(function ()
        {
            var rect = this.getBoundingClientRect();
            var sqX = rect.left + rect.width / 2;
            var sqY = rect.top + rect.height / 2;

            var distance = Math.sqrt((sqX - mouseX) ** 2 + (sqY - mouseY) ** 2);

            if (distance < 100)
            {
                $(this).addClass('highlighted-1').removeClass('highlighted-2 highlighted-3 highlighted');
            }
            else if (distance < 150)
            {
                $(this).addClass('highlighted-2').removeClass('highlighted-1 highlighted-3 highlighted');
            }
            else if (distance < 200)
            {
                $(this).addClass('highlighted-3').removeClass('highlighted-1 highlighted-2 highlighted');
            }
            else
            {
                $(this).removeClass('highlighted-1 highlighted-2 highlighted-3').addClass('highlighted');
            }

        });
    });

});