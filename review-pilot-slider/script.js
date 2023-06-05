function handleCheckboxVisibility(checkbox){
    let targetId = checkbox.getAttribute('id'); 
    let targetElement = document.querySelector('.rs-input-group[data-linked="' + targetId + '"]');
    if(checkbox.checked) {
        targetElement.style.display = 'block';
    } else {
        targetElement.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function(){

    /* UI/UX */
    /* vis skjul tjekbokse*/
    let checkboxes = document.querySelectorAll('input[type="checkbox"][data-linkedup]');
    checkboxes.forEach(function(checkbox){

        if(checkbox.checked){
            handleCheckboxVisibility(checkbox);
        }

        checkbox.addEventListener('change', function(){
            handleCheckboxVisibility(checkbox);
        });
    });

    /* kopi ikon */

    let copyBtn = document.querySelector('.copy-icon');
    let copyMessage = document.querySelector('.copy-icon .copied');
    copyBtn.addEventListener('click', function(){

        let copySource = document.querySelector('.shortcode-render-element').innerHTML;

        navigator.clipboard.writeText(copySource);
        console.log('copied: ' + copySource);
        copyMessage.classList.add('visible');
        setTimeout(function(){
            copyMessage.classList.remove('visible');
        }, 700);
    });



    /* get fields */
    let fields = new Array();
    document.querySelectorAll('.shortcode-settings-side *:is(input, select)').forEach(input => {
        fields.push(input);
    });
    console.log(fields);

    fields.forEach(field => {
        field.addEventListener('input', function(){
            renderShortcode();
        })
    })

    /* render shortcode*/

    let shortcodeRenderElement = document.querySelector('.shortcode-render-element');

    function renderShortcode(){
        console.log('rendering');

        let shortcode = new Array('review-slider');

        //vis header på slideren
        if(!fields[0].checked == true){
            shortcode.push('header="false"');
        }

        //Alternativ overskrift
        if(!fields[1].value == ""){
            shortcode.push('headline="' + fields[1].value + '"');
        }

        //autoplay
        if(fields[2].checked == true){
            shortcode.push('autoplay="true"');
        }

        //loop
        if(fields[3].checked == true){
            shortcode.push('loop="true"');
        }

        //slider interval
        if(!fields[4].value == ""){
            if(fields[2].checked == true){
                shortcode.push('interval="' + fields[4].value * 1000 + '"');
            }
        }

        //Stjernevisning interval
        if(fields[5].options[fields[5].selectedIndex].value != "both"){
            shortcode.push('stars="' + fields[5].options[fields[5].selectedIndex].value + '"');
        }

        //Generer schema data
        if(!fields[6].checked == true){
            shortcode.push('schema="false"');
        }

        //kategori tags
        if(!fields[7].value == ""){
            shortcode.push('apitags="' + fields[7].value + '"');
        }


        shortcodeRenderElement.innerHTML = '[' + shortcode.join(' ') + ']'

    }

    renderShortcode();

});
