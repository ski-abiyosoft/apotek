<script>
function convert_currency() {
    $("input[data-type='currency']").on({
        keyup: function() {
            formatCurrency($(this));
        },
        blur: function() {
            var val = this.value.replaceAll(',', '').split('.');
            this.value = currency_format(val[0]);
        }
    });
}

function convert_currency2() {
    $("input[data-type='currency']").on({

        blur: function() {
            var val = this.value.replaceAll(',', '').split('.');
            this.value = currency_format(val[0]);
        }
    });
}

function currency_format(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function isCharacterALetter(char) {
    return (/[a-zA-Z]/).test(char)
}

function formatCurrency(input, blur) {
    var input_val = input.val();

    if (input_val === "") {
        return;
    }

    // console.log(input_val);

    var original_len = input_val.length;

    var caret_pos = input.prop("selectionStart");

    if (input_val.indexOf(".") >= 0) {
        console.log('if');
        var decimal_pos = input_val.indexOf(".");

        var left_side = input_val.substring(0, decimal_pos);
        var right_side = input_val.substring(decimal_pos);

        left_side = formatNumber(left_side);
        right_side = formatNumber(right_side);

        if (blur === "blur") {
            right_side += "00";
        }

        right_side = right_side.substring(0, 2);

        input_val = "" + left_side + "." + right_side;

    } else {
        console.log('else');
        input_val = formatNumber(input_val);
        console.log('input_val : ' + input_val);
        input_val = "" + input_val;

        if (blur === "blur") {
            input_val += ".00";
        }
    }

    input.val(input_val);


    var updated_len = input_val.length;
    // console.log("updated_len: " + updated_len + "original_len: " + original_len + "caret_pos: " + caret_pos);
    caret_pos = updated_len - original_len + caret_pos;
    // console.log(caret):
    input[0].setSelectionRange(caret_pos, caret_pos);
    // console.log(input);
}
</script>