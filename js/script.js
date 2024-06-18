function setTodayDate(inputId) {
    let today = new Date();
    let yyyy = today.getFullYear();
    let mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
    let dd = String(today.getDate()).padStart(2, '0');

    let todayFormatted = yyyy + '-' + mm + '-' + dd;
    document.getElementById(inputId).value = todayFormatted;
}

export { setTodayDate };
