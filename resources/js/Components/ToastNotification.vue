<script setup>
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css'; // You can change the theme as needed

// Use the toast plugin
export const showToast = (type = 'success', msg) => {
  const toast = useToast();
  toast.open({
    message: msg,
    type: type, // success, error, warning, info
    duration: 3000,
    position: 'top-right',
  });
};
</script>
