let a = 5
let b = 10
a = a+b
b = a-b
a = a-b
console.log(a)
console.log(b)

function square(n){
    return n*n
}
for (let i = 1; i <= 10; i++) {
    x=square(i)
    console.log(i,"^2 = ",x);
}

const numbers = [12,5,1,15,7]
largest = numbers[0]
for (let i = 0; i < numbers.length; i++) {
    if(numbers[i]>largest){
        largest=numbers[i];
    }
}
    console.log("largest number = ",largest);
