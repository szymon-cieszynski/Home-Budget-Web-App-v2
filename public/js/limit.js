// const category = document.querySelector('#category');
// const date = document.querySelector('#date');
// const limitHeading = document.getElementById("limit");
// const sumFromSelectedMonth = document.getElementById("sum");
// const difference = document.getElementById('difference');
// let selectedCategory = null;
// let selectedDate = null;

// const renderOnDom = (json_limit, json_sum, diff) => {
//     if (json_limit === null) {
//         limitHeading.textContent = '';
//         difference.textContent = '';
//         sumFromSelectedMonth.textContent = '';
//     } else {
//         limitHeading.textContent = `Limit for selected category: ${json_limit} PLN`;

//         if (json_sum > 0) {
//             sumFromSelectedMonth.textContent = `Sum expenses from selected month: ${json_sum} PLN`;
//         } else {
//             sumFromSelectedMonth.style.color = "green";
//             sumFromSelectedMonth.textContent = `You have no expenses in selected month.`;
//         }

//         if (diff > 0) {
//             diff = parseFloat(diff).toFixed(2);
//             difference.style.color = "green";
//             difference.textContent = `Still avaliable from your limit: ${diff} PLN`;
//         } else {
//             difference.style.color = "red";
//             difference.textContent = `Your budget is exceeded! Difference: ${diff} PLN`;
//         }
//     }
// }

// const calculateLimits = (json_limit, json_sum) => {
//     const diff = json_limit - json_sum;
//     console.log(diff);
//     return diff;
// }

// const checkLimit = async () => {

//     const json_limit = await getLimitForCategory(selectedCategory);
//     const json_sum = await getSumOfExpensesForSelectedMonth(selectedCategory, selectedDate);

//     console.log(json_sum);
//     const diff = calculateLimits(json_limit, json_sum);
//     renderOnDom(json_limit, json_sum, diff);
// }

// category.addEventListener("change", async function () {
//     selectedCategory = category.value;
//     if (selectedCategory && selectedDate) {
//         checkLimit();
//     } else {
//         renderOnDom(null, null, null);
//     }
// });

// date.addEventListener("change", async function () {
//     selectedDate = date.value;
//     if (selectedCategory && selectedDate) {
//         checkLimit();
//     } else {
//         renderOnDom(null, null, null);
//     }
// });

// const getSumOfExpensesForSelectedMonth = async (category_id, date_id) => {
//     const res = await fetch(`http://localhost:8080/api/sum/${category_id}?date=${date_id}`);
//     const data = await res.json();
//     return data;
// }

// const getLimitForCategory = async (category_id) => {
//     const res = await fetch(`http://localhost:8080/api/limit/${category_id}`);
//     const json_lim = await res.json();
//     return json_lim;
// }