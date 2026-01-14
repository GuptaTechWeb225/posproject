<template>
    <div class="content-wrapper">
        <div class="row justify-content-center">
            <div class="">
                <div class="card">
                    <form :action="action_url" >
                        <button
                            id="card-button"
                            class="btn btn-dark"
                            type="submit"
                        >
                            {{$t('make_paypal_payment')}}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {PAYPAL_ACTION_URL} from "../../../Config/ApiUrl-CPB";

export default {
    name: "Paypal",
    data(){
        return{
            action_url: PAYPAL_ACTION_URL,
        }
    }
}
</script>
