<template>
    <div class="ac_tbl-item-wrp" @click="$emit('onmore')">
        <div class="ac_tbl-linetime-wrp">

                    <div class="ac_tbl-linetime-more">
                        <svg height="20" width="42">
                            <line x1="1" y1="0" x2="1" y2="20" style="stroke:#004165;stroke-width:2"></line> 
                            <circle cx="11" cy="10" r="3" fill="#004165"></circle>  
                            <circle cx="21" cy="10" r="3" fill="#004165"></circle>  
                            <circle cx="31" cy="10" r="3" fill="#004165"></circle>    
                            <line x1="41" y1="0" x2="41" y2="20" style="stroke:#004165;stroke-width:2"></line> 
                        </svg>
                    </div>
                    <div class="ac_tbl-line-more"></div>    



        </div>
        <div class="ac_tbl-item-content">
            <div class="ac_tbl-item-card">
                        <div class="ac_tbl-item-loadmore">
                            <img :src="image001" alt="">
                            <p>Cargar mas contenido</p>  
                        </div>                 
            </div>                    
        </div>
    </div>
</template>

<script>
import image01 from '@/components/tablero/calendario.png';

    export default {
        data: function(){
            return {
                image001: image01
            }
        }
    }


</script>
