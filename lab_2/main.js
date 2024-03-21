function AddToDisplay(symbol) {
    display.value += symbol;
}

function Clear() {
    display.value = "";
}

// function Equalize() {
//     var equation = display.value;
//     if (equation[0] == '+') {
//         equation = equation.slice(1);
//         eval('result=parseInt(' + equation + ')');
//     } else {
//         result = eval(equation);
//     }
//     display.value = "" + result;
// }


function Backscape() {
    const  backspace = display.value.length -  1;
    display.value = display.value.substring(0,backspace);
}
function Equalize() {
    const equal = display.value;
    fetch('index.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `equal=${encodeURIComponent(equal)}`
    })
    .then((response) => {
        return response.text()
    })
    .then(result => display.value = result)
    .catch(error => console.error('Ошибка:', error));
}
