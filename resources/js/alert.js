import Swal from "sweetalert2";

export function success(page){
    Swal.fire({
        icon: "success",
        title: page.props.flash.success,
        toast: true,
        position: "top-right",
        showConfirmButton: false,
        timer: 4000, 
        showCloseButton: true,
        timerProgressBar: true
    });
}

export function error(page){
    Swal.fire({
        icon: "error",
        title: page.props.flash.error,
        toast: true,
        position: "top-right",
        showConfirmButton: false,
        timer: 4000, 
        showCloseButton: true,
        timerProgressBar: true
    });
}

export function warning(){
    return Swal.fire({
        title: 'Are you sure to delete ?',
        text: "This actions cannot undo!",
        icon: 'warning',
        showCancelButton: true,
        showCloseButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'No',
        confirmButtonText: 'Yes'
    });
}