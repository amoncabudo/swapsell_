<script setup>
import Footer from '../Components/Footer.vue';
import { ref } from 'vue';
import Accordion from 'primevue/accordion';
import AccordionTab from 'primevue/accordiontab';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavbarS from '@/Layouts/NavbarS.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    isAuthenticated: Boolean,
});

const items = ref([
    { label: 'Inici', url: '/' },
    { label: 'Avis Legal' }
]);
</script>
<template>
    <Head title = "Avis legal"></Head>
    <component :is="isAuthenticated ? AuthenticatedLayout : NavbarS">
    <div class="min-h-screen bg-gray-50">
        <main class="container mx-auto px-4 py-8">
            <div class="max-w-4xl mx-auto">
                <Breadcrumb :model="items" class="mb-8" />
                <h1 class="text-4xl font-bold text-gray-800 mb-8" data-aos="fade-down">Avis Legal</h1>

                <div class="bg-white rounded-xl shadow-lg p-8" data-aos="fade-up" data-aos-delay="200">
                    <Accordion>
                        <AccordionTab header="1. Identificació del Titular" data-aos="fade-right" data-aos-delay="300" class="title-tab" >
                            <div data-aos="fade-up" data-aos-delay="400">
                                <p class="m-0">
                                    En compliment de les disposicions legals aplicables, s'informa els usuaris
                                    que la present pàgina web i aplicació (d'ara endavant, "la Plataforma") és operada
                                    per:
                                </p>
                                <ul class="p-ul">
                                    <li>Nom d'Empresa: SwapSell</li>
                                    <li>NIF/CIF: B12345678</li>
                                    <li>Domicili Social: C/Pelai Martinez, 1, 17600 Figueres, Girona, Espanya</li>
                                    <li>Correu Electrònic de Contacte: info@comprasegunda.com</li>
                                    <li>Telèfon de Contacte: +34 972 123 456</li>
                                </ul>
                            </div>
                        </AccordionTab>

                        <AccordionTab header="2. Objecte i Acceptació" data-aos="fade-right" data-aos-delay="400">
                            <div data-aos="fade-up" data-aos-delay="500">
                                <p class="m-0">
                                    El present Avís Legal regula l'accés i ús de la Plataforma, el propòsit
                                    principal de la qual és facilitar la compra i venda d'objectes de segona mà entre
                                    usuaris. L'accés i ús de la Plataforma implica l'acceptació plena dels termes i
                                    condicions aquí descrits.
                                </p>
                            </div>
                        </AccordionTab>

                        <AccordionTab header="3. Condicions d'Ús" data-aos="fade-right" data-aos-delay="500">
                            <div data-aos="fade-up" data-aos-delay="600">
                                <p class="m-0">
                                    Els usuaris es comprometen a utilitzar la Plataforma de manera lícita, respectant la
                                    legislació vigent, la moral i l'ordre públic.
                                    Queda prohibit utilitzar la Plataforma amb finalitats fraudulents o per a cometre
                                    actes il·lícits.
                                    Els usuaris seran responsables de la veracitat i exactitud de les dades
                                    proporcionades en els seus comptes i anuncis.
                                </p>
                            </div>
                        </AccordionTab>

                        <AccordionTab header="4. Exempció de Responsabilitat" data-aos="fade-right" data-aos-delay="600">
                            <div data-aos="fade-up" data-aos-delay="700">
                                <p class="m-0">
                                    Relació entre Usuaris: La Plataforma actua únicament com un intermediari per a
                                    connectar a compradors i venedors. No garanteix la qualitat, seguretat, autenticitat
                                    o legalitat dels productes anunciats ni participa en les transaccions realitzades.
                                    Disponibilitat del Servei: No garantim la disponibilitat contínua de la Plataforma,
                                    podent suspendre's temporalment per raons tècniques o de manteniment.
                                </p>
                            </div>
                        </AccordionTab>

                        <AccordionTab header="5. Drets de Propietat Intel·lectual" data-aos="fade-right" data-aos-delay="700">
                            <div data-aos="fade-up" data-aos-delay="800">
                                <p class="m-0">
                                    Tots els continguts, dissenys, textos, gràfics, imatges, logotips i altres elements
                                    de la Plataforma són propietat del titular o de tercers autoritzats, quedant
                                    prohibida la seva reproducció, distribució, comunicació pública o transformació
                                    sense l'autorització prèvia i per escrit del titular.
                                </p>
                            </div>
                        </AccordionTab>

                        <AccordionTab header="6. Política de Privacitat i Cookies" data-aos="fade-right" data-aos-delay="800">
                            <div data-aos="fade-up" data-aos-delay="900">
                                <p class="m-0">
                                    Per a més informació sobre el tractament de dades personals i l'ús de cookies, si us
                                    plau consulta nostra <a href="#" class="text-blue-600 hover:underline">Política de Privacitat</a> i 
                                    <a href="#" class="text-blue-600 hover:underline">Política de Cookies</a>, disponibles en la Plataforma.
                                </p>
                            </div>
                        </AccordionTab>

                        <AccordionTab header="7. Modificacions" data-aos="fade-right" data-aos-delay="900">
                            <div data-aos="fade-up" data-aos-delay="1000">
                                <p class="m-0">
                                    El titular es reserva el dret de modificar el present Avís Legal en qualsevol
                                    moment, informant prèviament els usuaris mitjançant la Plataforma.
                                </p>
                            </div>
                        </AccordionTab>

                        <AccordionTab header="8. Legislació Aplicable i Jurisdicció" data-aos="fade-right" data-aos-delay="1000">
                            <div data-aos="fade-up" data-aos-delay="1100">
                                <p class="m-0">
                                    Aquest Avís Legal es regeix per la legislació vigent a Espanya. Per a la resolució
                                    de qualsevol controvèrsia, les parts se sotmeten als jutjats i tribunals de
                                    Figueres, Girona, tret que la normativa aplicable disposi el contrari.
                                </p>
                            </div>
                        </AccordionTab>
                    </Accordion>
                </div>
            </div>
        </main>
        <Footer />
    </div>
</component>
</template>



<style scoped>
:deep(.p-accordion .p-accordion-header .p-accordion-header-link) {
    background-color: transparent;
    color: #1f2937;
    border: none;
    padding: 1.25rem;
    font-weight: 500;
    transition: all 0.3s ease;
    border-radius: 0;
    margin-bottom: 0;
    border-bottom: 1px solid #e5e7eb;
}

:deep(.p-accordion .p-accordion-header:not(.p-disabled).p-highlight .p-accordion-header-link) {
    background-color: transparent;
    color: #3b82f6;
    border-bottom: 2px solid #3b82f6;
}

:deep(.p-accordion .p-accordion-header:not(.p-disabled) .p-accordion-header-link:hover) {
    background-color: #f8fafc;
    color: #3b82f6;
}

:deep(.p-accordion .p-accordion-content) {
    background-color: #f8fafc;
    color: #4b5563;
    padding: 1.5rem;
    border: none;
    border-bottom: 1px solid #e5e7eb;
    margin-bottom: 0;
}
:deep(.p-accordionpanel:not(.p-accordionpanel-active):not(.p-disabled) > .p-accordionheader:hover) {
    background-color: #f8fafc;
    color: #004266;
    svg {
        color: #004266;
    }
}
:deep(.p-accordionpanel > .p-accordionheader){
    color: #585959;
}
:deep(.p-accordioncontent-content){
    background-color: transparent;
    color: #4b5563;
    padding: 1.5rem;
    border: none;
    border-bottom: 1px solid #e5e7eb;
    margin-bottom: 0;
}
:deep(.p-accordion .p-accordion-header .p-accordion-header-link .p-accordion-toggle-icon) {
    color: #6b7280;
    transition: transform 0.3s ease;
}

:deep(.p-accordion .p-accordion-header:not(.p-disabled).p-highlight .p-accordion-header-link .p-accordion-toggle-icon) {
    color: #004266;
    transform: rotate(180deg);
}

:deep(button) {
    background-color: transparent;
}
:deep(.p-breadcrumb) {
    background-color: transparent;
}
:deep(.p-breadcrumb-item-label) {
    color: #004266;
}

:deep(.p-breadcrumb-item-label:hover) {
    color: #060606 !important;
}


</style>
