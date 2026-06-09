<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "Organization",
  "name": "{{ config('laksana.brand.company') }}",
  "url": "{{ config('app.url') }}",
  "logo": "{{ asset(config('laksana.brand.logos.main')) }}",
  "email": "{{ config('laksana.brand.email') }}",
  "telephone": "{{ config('laksana.brand.phone') }}",
  "address": {
    "@@type": "PostalAddress",
    "streetAddress": "{{ config('laksana.brand.address') }}",
    "addressCountry": "ID"
  }
}
</script>
