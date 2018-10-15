jQuery(document).ready(function ($) {
    /* =======================================================================================================
      * check URL and see if it contains query string using getParams function
      * get the attributes from the url query string and pass them to the ajaxCall function
      * on click check if the attribute already in URL and use queryStringUrlReplacement to replace the value
      * check if should append ? or &
      * ajax call pass the attributes array to function.php using POST
      * =======================================================================================================
     */

    /* useful javascript script to return all string values from url */
    /*
    var urlParams;
    (window.onpopstate = function () {
        var match,
            pl     = /\+/g,  // Regex for replacing addition symbol with a space
            search = /([^&=]+)=?([^&]*)/g,
            decode = function (s) { return decodeURIComponent(s.replace(pl, " ")); },
            query  = window.location.search.substring(1);

        urlParams = {};
        while (match = search.exec(query))
            urlParams[decode(match[1])] = decode(match[2]);
    })();
    console.log(urlParams);*/


    function removeURLParameter(url, parameter) {
        //prefer to use l.search if you have a location/link object
        var urlparts= url.split('?');
        if (urlparts.length>=2) {

            var prefix= encodeURIComponent(parameter)+'=';
            var pars= urlparts[1].split(/[&;]/g);

            //reverse iteration as may be destructive
            for (var i= pars.length; i-- > 0;) {
                //idiom for string.startsWith
                if (pars[i].lastIndexOf(prefix, 0) !== -1) {
                    pars.splice(i, 1);
                }
            }

            url= urlparts[0] + (pars.length > 0 ? '?' + pars.join('&') : "");
            return url;
        } else {
            return url;
        }
    }



    $('.hidden-attributes').each(function () {
       // console.log($(this).attr('data-attr'));
    });


    // function to parse the url and retreive all params
    var getParams = function (url) {
        var params = {};
        var parser = document.createElement('a');
        parser.href = url;
        var query = parser.search.substring(1);
        var vars = query.split('&');
        if(vars == ''){
            params = '';
            return params;
        }
        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split('=');
            params[pair[0]] = decodeURIComponent(pair[1]);
        }
        return params;
    };


    function initAjax(archiveTax = '', archiveTerm = ''){
        // Get parameters from the current URL
        let href = getParams(window.location.href);
        //console.log(href);

        let attributes,
            values = [];
        //loop through the returned obj
        Object.keys(href).forEach(function(key) {
             attributes = key;
             values[attributes] = [href[key]];
            $('.hmu_filter_attributes').each(function () {
               let term = $(this).attr('data-term');
               if(term == href[key]){
                   $(this).attr("checked", "checked");
               }
            });
            //ajaxCall( values, attributes,  'recyclable-waste', true);
        });
        if(href === ''){
           // console.log('no ajax attr');
        }else{
            let archive = {archiveTerm}

            if(archiveTerm !=='' && archiveTerm !== '') {
                href = {
                    ...href,
                    [archiveTax]:archiveTerm ,

                }
            }
            console.log(href)

            ajaxCall( href,true);
            // add checked to relevant inputs

        }

    }
    initAjax();

    // function to change the value of url string
    function queryStringUrlReplacement(url, param, value)
    {
        var re = new RegExp("[\\?&]" + param + "=([^&#]*)"), match = re.exec(url), delimiter, newString;

        if (match === null) {

            // append new param
            var hasQuestionMark = /\?/.test(url);
            delimiter = hasQuestionMark ? "&" : "?";
            newString = url + delimiter + param + "=" + value;
        } else {
            delimiter = match[0].charAt(0);
            newString = url.replace(re, delimiter + param + "=" + value);
        }

        return newString;
    }


    $('.hmu_filter_attributes').on('click', function (e) {
      //  e.preventDefault();
        var location = window.location.href;


        if( $(this).is(':checked') ) {


            var termTax = $(this).attr('data-term-tax');
            var termId = $(this).attr('data-term');
            var termSlug = $(this).attr('data-term-slug');
            var archiveTax = $(this).attr('data-archive-tax');
            var archiveTerm = $(this).attr('data-archive-term');
           // var termHref = $(this).find(":checked").attr('href');
           // console.log(archiveTax + archiveTerm);

            if (location.indexOf(termTax) != -1) {
                //console.log(queryStringUrlReplacement(window.location.href, termTax, termSlug));
                var newURL = queryStringUrlReplacement(window.location.href, termTax, termSlug);
                window.history.pushState("", "", newURL);
                //
            } else {
                if (location.indexOf('?') != -1)
                    window.history.pushState("", "", location + '&' + termTax + '=' + termSlug);

                else
                    window.history.pushState("", "", location + '?' + termTax + '=' + termSlug);

            }
            var hmuClass = $('.hmu-'+termTax);


            $(this).closest('.hmu-term-container').each(function() {

                $(this).siblings().find($('.hmu_filter_attributes')).each(function(){
                    $(this).prop('checked', false);
                });

            });
           // $(this).prop('checked', true);
            //   $(this).off("click").attr('data-url', termHref).attr('href', "javascript: void(0);").addClass('active');
            $(this).parent().addClass('parent-active');
            $(this).parent().siblings().removeClass('parent-active');

            //
            // console.log(termTax);


        }else {
            var termTax = $(this).attr('data-term-tax');
            var termId = $(this).attr('data-term');
            var termSlug = $(this).attr('data-term-slug');
            if (location.indexOf(termTax) != -1) {
                var newURL = removeURLParameter(location, termTax)
                window.history.pushState("", "", newURL);

                //
            }
        }
        initAjax(archiveTax , archiveTerm);
    });





    //function ajaxCall( slug, termTax,  term_relation, arg) {
    function ajaxCall( href,  arg) {

        const adminAjax = ajax_var.url;
        const nonce = ajax_var.nonce;
        let wrapperID = ajax_var.wrapper_id;
        console.log('#'+wrapperID);

        $.ajax({
            url: adminAjax ,
            data : {
                action : 'customfilter',
                nonce: nonce,
                /*tax : termTax,
                relation : term_relation,
                term : slug,*/
                attributes: href,
                args : arg,

            },
            type:'POST', // POST
            beforeSend:function(xhr){
                $('body').addClass('load-ajax');
            },
            error:function (data) {
                $('body').removeClass('load-ajax');
                console.log('ERROR');
            },
            success:function(data){
                $('body').removeClass('load-ajax');
                if(data =='') {
                    console.log('empty');
                }else {

                    $('#'+wrapperID).empty().html(data);
                }


                // $('#lazyload').empty();
            }
        });
    }

});



