import Swal from 'sweetalert2';

export default {
  methods: {
    deleteModal(view, cb) {
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then(result => {
        if (result.value) {
          this.$http.delete(`laravelunused/delete/${view}`).then(response => {
            cb();

            Swal.fire('Deleted!', 'Your file has been deleted.', 'success');
          });
        }
      });
    }
  }
};
