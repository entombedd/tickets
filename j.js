var reserve = {
  toggle : seat => seat.classList.toggle("selected"),
  save : () => {
    var selected = document.querySelectorAll("#layout .selected");
    if (selected.length == 0) {
      alert("No seats selected.");
      return false;
    }
    else {
      var form = document.getElementById("form");
      for (let seat of selected) {
        let input = document.createElement("input");
        input.type = "hidden";
        input.name = "seats[]";
        input.value = seat.innerHTML;
        form.appendChild(input);
      }
      return true;
    }
  }
};