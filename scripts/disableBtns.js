// Disable comment button
function disableCommentBtn() {
    //Disable Comment Button
    document.querySelector("#commentBtn").disabled = true;
    //Change Comment Title When Comment is Submitted
    document.getElementById("commentTitle").innerHTML = "Commenting...";
}