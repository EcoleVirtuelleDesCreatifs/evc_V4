@extends('layouts.app')

@section('title', $product->title . ' - EVC Store')
@section('description', strip_tags($product->summary ?: $product->description))

@push('styles')
<style>
    .store-product-page {
        padding: 320px 20px 80px !important;
    }
    .product-detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 50px;
        align-items: start;
    }
    .store-product-page h1 {
        font-size: 42px;
    }

    @media (max-width: 991px) {
        .store-product-page {
            padding: 120px 16px 60px !important;
        }
        .store-product-page h1 {
            font-size: 26px !important;
            line-height: 1.2;
            overflow-wrap: break-word;
        }
        .product-detail-grid {
            grid-template-columns: 1fr !important;
            gap: 24px;
        }
        .product-detail-grid > div {
            width: 100%;
            min-width: 0;
        }
        .product-gallery {
            order: -1;
        }
        .product-image-stage {
            aspect-ratio: auto !important;
            height: auto !important;
            min-height: 240px;
            padding: 16px;
        }
        .product-main-image,
        #gallery-main {
            width: 100% !important;
            height: auto !important;
            max-height: 60vh;
            object-fit: contain !important;
            border-radius: 16px;
        }
        .product-info {
            width: 100%;
            min-width: 0;
        }
        .product-info .product-price-block,
        .product-info .product-availability,
        .product-info .product-variants-list,
        .product-info .product-radio-group,
        .product-info .order-total-row {
            flex-direction: column !important;
            align-items: flex-start !important;
        }
        #product-order-form > div {
            grid-template-columns: 1fr !important;
        }
        .store-product-page #order-total-display {
            font-size: 18px !important;
        }
    }
</style>
@endpush

@section('content')
@php
    $productImages = collect([$product->image])->merge($product->images ?? [])->map(fn($img) => \App\Models\MediaUrl::fromPath($img))->filter()->values()->all();
@endphp
<div class="store-product-page" style="min-height: 100vh; background: linear-gradient(135deg, #0a0e27 0%, #151a3d 50%, #0d1333 100%); color: #fff; padding: 320px 20px 80px;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <a href="{{ route('evc.store') }}" style="display: inline-flex; align-items: center; gap: 8px; color: #ff6b35; text-decoration: none; margin-bottom: 30px; font-weight: 600;">
            <i class="fas fa-arrow-left"></i> Retour à la boutique
        </a>

        <div class="product-detail-grid">
            <!-- Images -->
            <div class="product-gallery">
                <div class="product-image-stage" style="position: relative; border-radius: 24px; overflow: hidden; background: rgba(21,26,61,0.6); border: 1px solid rgba(255,255,255,0.08); aspect-ratio: 1/1; display: flex; align-items: center; justify-content: center;">
                    <img id="gallery-main" class="product-main-image" src="{{ $productImages[0] ?? '' }}" alt="{{ $product->title }}" style="width: 100%; height: 100%; object-fit: cover; transition: opacity 0.3s;">

                    @if(count($productImages) > 1)
                        <button type="button" onclick="prevImage()" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); width: 42px; height: 42px; border-radius: 50%; border: none; background: rgba(0,0,0,0.5); color: #fff; cursor: pointer; font-size: 18px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button type="button" onclick="nextImage()" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); width: 42px; height: 42px; border-radius: 50%; border: none; background: rgba(0,0,0,0.5); color: #fff; cursor: pointer; font-size: 18px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    @endif

                    @if(empty($productImages))
                        <i class="fas fa-image" style="font-size: 5rem; color: #ff6b35;"></i>
                    @endif
                </div>

                @if(count($productImages) > 1)
                    <div id="gallery-dots" style="display: flex; justify-content: center; gap: 10px; margin-top: 18px;">
                        @foreach($productImages as $index => $img)
                            <button type="button" onclick="showImage({{ $index }})" data-index="{{ $index }}" style="width: 10px; height: 10px; border-radius: 50%; border: none; background: {{ $index === 0 ? '#ff6b35' : 'rgba(255,255,255,0.3)' }}; cursor: pointer; padding: 0;"></button>
                        @endforeach
                    </div>
                @endif

                @if(count($productImages) > 1)
                    <div id="gallery-thumbnails" style="display: flex; gap: 12px; margin-top: 18px; overflow-x: auto; scroll-snap-type: x mandatory; scrollbar-width: thin;">
                        @foreach($productImages as $index => $img)
                            <button type="button" onclick="showImage({{ $index }})" data-thumb="{{ $index }}" style="flex: 0 0 80px; width: 80px; height: 80px; border-radius: 12px; overflow: hidden; border: {{ $index === 0 ? '2px solid #ff6b35' : '1px solid rgba(255,255,255,0.1)' }}; background: none; padding: 0; cursor: pointer; scroll-snap-align: start;">
                                <img src="{{ $img }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Détails + Commande -->
            <div class="product-info">
                <span style="display: inline-block; padding: 6px 16px; background: rgba(255,107,53,0.15); border: 1px solid rgba(255,107,53,0.3); border-radius: 20px; color: #ff6b35; font-size: 13px; font-weight: 600; margin-bottom: 16px;">
                    {{ $product->category->name ?? 'Boutique EVC' }}
                </span>

                <h1 style="font-size: 42px; font-weight: 800; margin-bottom: 16px; line-height: 1.2;">{{ $product->title }}</h1>

                <div class="product-price-block" style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px; flex-wrap: wrap;">
                    <div style="font-size: 28px; font-weight: 700; color: #ff6b35;">
                        {{ $product->formatted_price }}
                    </div>
                    @if($product->is_promotion && $product->formatted_old_price)
                        <div style="font-size: 20px; text-decoration: line-through; opacity: 0.6;">
                            {{ $product->formatted_old_price }}
                        </div>
                        <div style="display: inline-block; padding: 4px 12px; background: #ff4757; border-radius: 8px; font-size: 13px; font-weight: 700;">
                            Promo
                        </div>
                    @endif
                </div>

                @php
                    $stockLabel = match($product->stock_status) {
                        'en_stock' => 'En stock',
                        'stock_limite' => 'Stock limité',
                        default => 'Rupture',
                    };
                    $stockColor = match($product->stock_status) {
                        'en_stock' => '#00d4aa',
                        'stock_limite' => '#ff9800',
                        default => '#ff4757',
                    };
                @endphp
                <div class="product-availability" style="display: flex; align-items: center; gap: 8px; margin-bottom: 24px; font-weight: 600; color: {{ $stockColor }};">
                    <i class="fas fa-circle" style="font-size: 8px;"></i>
                    {{ $stockLabel }} @if($product->stock > 0)({{ $product->stock }} disponible{{ $product->stock > 1 ? 's' : '' }})@endif
                </div>

                @if($product->summary)
                    <p style="font-size: 18px; color: #a0aec0; margin-bottom: 24px; line-height: 1.6;">{{ $product->summary }}</p>
                @endif

                @if($product->description)
                    <div style="color: #cbd5e1; line-height: 1.7; margin-bottom: 32px; white-space: pre-line;">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                @endif

                @if(!empty($product->variants) && is_array($product->variants))
                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 12px;">Options disponibles</label>
                        <div class="product-variants-list" style="display: flex; flex-wrap: wrap; gap: 10px;">
                            @foreach($product->variants as $variant)
                                <span style="padding: 8px 16px; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; font-size: 14px;">
                                    {{ is_array($variant) ? ($variant['label'] ?? json_encode($variant)) : $variant }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($product->stock > 0)
                    <form id="product-order-form" style="background: rgba(21,26,61,0.6); border: 1px solid rgba(255,255,255,0.08); border-radius: 24px; padding: 28px;">
                        <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 20px;">Commander ce produit</h3>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #a0aec0;">Nom <span style="color: #ff6b35;">*</span></label>
                                <input type="text" name="nom" required style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.3); color: #fff;">
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #a0aec0;">Prénoms <span style="color: #ff6b35;">*</span></label>
                                <input type="text" name="prenoms" required style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.3); color: #fff;">
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #a0aec0;">Téléphone <span style="color: #ff6b35;">*</span></label>
                                <input type="tel" name="numero" required style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.3); color: #fff;">
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #a0aec0;">Quantité <span style="color: #ff6b35;">*</span></label>
                                <input type="number" id="order-qty" name="qty" min="1" max="{{ $product->stock }}" value="1" required style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.3); color: #fff;">
                            </div>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #a0aec0;">Adresse / Lieu de retrait <span style="color: #ff6b35;">*</span></label>
                            <input type="text" name="lieu" required style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.3); color: #fff;">
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                            <div>
                                <label style="display: block; margin-bottom: 10px; font-weight: 500; color: #a0aec0;">Mode de réception <span style="color: #ff6b35;">*</span></label>
                                <div class="product-radio-group" style="display: flex; gap: 16px; flex-wrap: wrap;">
                                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                        <input type="radio" name="delivery_mode" value="delivery" checked onchange="updateTotal()"> Livraison
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                        <input type="radio" name="delivery_mode" value="pickup" onchange="updateTotal()"> Retrait
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 10px; font-weight: 500; color: #a0aec0;">Paiement <span style="color: #ff6b35;">*</span></label>
                                <div class="product-radio-group" style="display: flex; gap: 16px; flex-wrap: wrap;">
                                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                        <input type="radio" name="payment_method" value="cash" checked> Espèces
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                        <input type="radio" name="payment_method" value="mobile_money"> Mobile Money
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #a0aec0;">Autres informations</label>
                            <textarea name="autre" rows="3" style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.3); color: #fff; resize: vertical;"></textarea>
                        </div>

                        <div class="order-total-row" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px; margin-bottom: 20px;">
                            <div style="font-size: 22px; font-weight: 700;" id="order-total-display">
                                Total : {{ number_format($product->price + $product->delivery_cost, 0, ',', ' ') }} FCFA
                            </div>
                            <button type="submit" id="order-submit" style="padding: 14px 32px; background: linear-gradient(135deg, #ff6b35, #ff4757); border: none; border-radius: 12px; color: #fff; font-weight: 700; font-size: 16px; cursor: pointer; display: inline-flex; align-items: center; gap: 10px;">
                                <i class="fas fa-shopping-bag"></i> Commander
                            </button>
                        </div>

                        <div id="order-message" style="display: none; padding: 14px; border-radius: 12px; font-weight: 600;"></div>
                    </form>
                @else
                    <div style="padding: 20px; border-radius: 16px; background: rgba(255,71,87,0.1); border: 1px solid rgba(255,71,87,0.3); color: #ff6b6b; font-weight: 600;">
                        <i class="fas fa-times-circle"></i> Ce produit est actuellement en rupture de stock.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const price = {{ $product->price }};
        const deliveryCost = {{ $product->delivery_cost ?? 0 }};
        const productId = {{ $product->id }};
        const productName = @json($product->title);
        const productEmail = @json($product->email);
        const images = @json($productImages);
        let currentImage = 0;

        function formatPrice(value) {
            return new Intl.NumberFormat('fr-FR').format(value) + ' FCFA';
        }

        window.updateTotal = function() {
            const qty = parseInt(document.getElementById('order-qty').value) || 1;
            const isDelivery = document.querySelector('input[name="delivery_mode"]:checked').value === 'delivery';
            const shipping = isDelivery ? deliveryCost : 0;
            const total = (price * qty) + shipping;
            document.getElementById('order-total-display').textContent = 'Total : ' + formatPrice(total);
            return { qty, total, shipping };
        };

        document.getElementById('order-qty').addEventListener('input', updateTotal);

        document.getElementById('product-order-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = e.target;
            const btn = document.getElementById('order-submit');
            const msg = document.getElementById('order-message');

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const { qty, total, shipping } = updateTotal();

            const order = {
                nom: form.nom.value.trim(),
                prenoms: form.prenoms.value.trim(),
                numero: form.numero.value.trim(),
                lieu: form.lieu.value.trim(),
                delivery_mode: form.delivery_mode.value,
                payment_method: form.payment_method.value,
                autre: form.autre.value.trim(),
                items: [{
                    id: productId,
                    name: productName,
                    price: price,
                    delivery_cost: shipping,
                    qty: qty,
                    email: productEmail,
                }],
                total: total,
            };

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi...';

            try {
                const res = await fetch('{{ route('evc.store.order', [], false) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(order)
                });

                const data = await res.json();

                if (data.success) {
                    msg.style.display = 'block';
                    msg.style.background = 'rgba(0,212,170,0.15)';
                    msg.style.border = '1px solid rgba(0,212,170,0.3)';
                    msg.style.color = '#00d4aa';
                    msg.innerHTML = '<i class="fas fa-check-circle"></i> ' + (data.message || 'Commande enregistrée avec succès.');
                    form.reset();
                    updateTotal();
                } else {
                    throw new Error(data.message || 'Erreur lors de la commande');
                }
            } catch (err) {
                msg.style.display = 'block';
                msg.style.background = 'rgba(255,71,87,0.15)';
                msg.style.border = '1px solid rgba(255,71,87,0.3)';
                msg.style.color = '#ff6b6b';
                msg.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + err.message;
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-shopping-bag"></i> Commander';
            }
        });

        window.showImage = function(index) {
            if (!images.length) return;
            currentImage = (index + images.length) % images.length;
            const main = document.getElementById('gallery-main');
            if (main) {
                main.style.opacity = '0.6';
                setTimeout(() => {
                    main.src = images[currentImage];
                    main.style.opacity = '1';
                }, 150);
            }

            document.querySelectorAll('#gallery-dots button').forEach((dot, i) => {
                dot.style.background = i === currentImage ? '#ff6b35' : 'rgba(255,255,255,0.3)';
            });

            document.querySelectorAll('#gallery-thumbnails button').forEach((thumb, i) => {
                thumb.style.border = i === currentImage ? '2px solid #ff6b35' : '1px solid rgba(255,255,255,0.1)';
                if (i === currentImage) thumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            });
        };

        window.nextImage = function() { showImage(currentImage + 1); };
        window.prevImage = function() { showImage(currentImage - 1); };
    })();
</script>
@endsection
