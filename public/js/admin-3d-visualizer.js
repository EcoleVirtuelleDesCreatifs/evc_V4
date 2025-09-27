/**
 * ADMIN 3D VISUALIZER - REVOLUTIONARY INTERFACE
 * Moteur de visualisation 3D immersive pour statistiques admin
 * Version: 1.0 - Revolutionary Edition
 */

class Admin3DVisualizer {
    constructor(options = {}) {
        this.config = {
            enableParticles: true,
            enableHolographicCards: true,
            particleCount: 1000,
            animationSpeed: 1.0,
            autoRotate: true,
            debug: true,
            ...options
        };

        this.scene = null;
        this.camera = null;
        this.renderer = null;
        this.controls = null;
        
        this.dataCards = new Map();
        this.particleSystems = new Map();
        this.animationFrameId = null;
        this.clock = null;
        
        this.raycaster = null;
        this.mouse = new THREE.Vector2();
        this.hoveredObject = null;
        
        this.isInitialized = false;
        this.log('Admin3DVisualizer initialized');
    }

    async init(container = '#stats-3d-container') {
        if (this.isInitialized) return;

        this.log('🚀 Initializing Revolutionary 3D Interface...');
        
        try {
            if (typeof THREE === 'undefined') {
                throw new Error('Three.js not loaded');
            }
            
            await this.initializeCore(container);
            await this.createSpatialEnvironment();
            await this.initializeParticleSystems();
            await this.createHolographicCards();
            await this.setupInteractionSystem();
            await this.startRenderLoop();
            
            this.isInitialized = true;
            this.log('✅ Revolutionary 3D Interface initialized');
            this.emitEvent('3d-visualizer:initialized');
            
        } catch (error) {
            this.log('❌ Failed to initialize 3D interface:', error);
            throw error;
        }
    }

    async initializeCore(container) {
        const containerElement = document.querySelector(container);
        if (!containerElement) {
            throw new Error(`Container ${container} not found`);
        }

        // Créer la scène 3D
        this.scene = new THREE.Scene();
        this.scene.background = new THREE.Color(0x0a0a0a);
        this.scene.fog = new THREE.Fog(0x0a0a0a, 10, 100);

        // Configurer la caméra
        const aspect = containerElement.clientWidth / containerElement.clientHeight;
        this.camera = new THREE.PerspectiveCamera(75, aspect, 0.1, 1000);
        this.camera.position.set(0, 5, 15);

        // Créer le renderer WebGL
        this.renderer = new THREE.WebGLRenderer({
            antialias: true,
            alpha: true,
            powerPreference: 'high-performance'
        });
        
        this.renderer.setSize(containerElement.clientWidth, containerElement.clientHeight);
        this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        this.renderer.shadowMap.enabled = true;
        this.renderer.shadowMap.type = THREE.PCFSoftShadowMap;

        containerElement.appendChild(this.renderer.domElement);

        // Contrôles orbitaux
        if (typeof THREE.OrbitControls !== 'undefined') {
            this.controls = new THREE.OrbitControls(this.camera, this.renderer.domElement);
            this.controls.enableDamping = true;
            this.controls.autoRotate = this.config.autoRotate;
            this.controls.autoRotateSpeed = 0.5;
        }

        this.clock = new THREE.Clock();
        this.raycaster = new THREE.Raycaster();
    }

    async createSpatialEnvironment() {
        // Éclairage ambiant
        const ambientLight = new THREE.AmbientLight(0x404040, 0.3);
        this.scene.add(ambientLight);

        // Lumière directionnelle
        const directionalLight = new THREE.DirectionalLight(0x3399ff, 1.0);
        directionalLight.position.set(10, 10, 5);
        directionalLight.castShadow = true;
        this.scene.add(directionalLight);

        // Lumières colorées pour ambiance
        const colors = [0xff6633, 0x3399ff, 0x28a745, 0xffc107];
        colors.forEach((color, index) => {
            const light = new THREE.PointLight(color, 0.5, 20);
            const angle = (index / colors.length) * Math.PI * 2;
            light.position.set(
                Math.cos(angle) * 8,
                2 + Math.sin(angle * 2) * 2,
                Math.sin(angle) * 8
            );
            this.scene.add(light);
        });

        // Grille holographique
        const gridHelper = new THREE.GridHelper(20, 20, 0x3399ff, 0x333333);
        gridHelper.material.opacity = 0.3;
        gridHelper.material.transparent = true;
        this.scene.add(gridHelper);

        // Champ d'étoiles
        await this.createStarField();
        
        // Plateforme centrale
        await this.createHolographicPlatform();
    }

    async createStarField() {
        const starsGeometry = new THREE.BufferGeometry();
        const starsCount = 2000;
        const positions = new Float32Array(starsCount * 3);

        for (let i = 0; i < starsCount * 3; i++) {
            positions[i] = (Math.random() - 0.5) * 200;
        }

        starsGeometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));

        const starsMaterial = new THREE.PointsMaterial({
            color: 0xffffff,
            size: 0.5,
            transparent: true,
            opacity: 0.8
        });

        const stars = new THREE.Points(starsGeometry, starsMaterial);
        this.scene.add(stars);
    }

    async createHolographicPlatform() {
        const geometry = new THREE.CylinderGeometry(8, 8, 0.2, 32);
        const material = new THREE.MeshPhongMaterial({
            color: 0x3399ff,
            transparent: true,
            opacity: 0.3,
            emissive: 0x001133
        });

        const platform = new THREE.Mesh(geometry, material);
        platform.position.y = -2;
        this.scene.add(platform);

        // Anneaux holographiques
        for (let i = 1; i <= 3; i++) {
            const ringGeometry = new THREE.RingGeometry(8 + i, 8.2 + i, 32);
            const ringMaterial = new THREE.MeshBasicMaterial({
                color: 0x3399ff,
                transparent: true,
                opacity: 0.2 / i,
                side: THREE.DoubleSide
            });

            const ring = new THREE.Mesh(ringGeometry, ringMaterial);
            ring.position.y = -1.9 + i * 0.1;
            ring.rotation.x = -Math.PI / 2;
            this.scene.add(ring);
        }
    }

    async initializeParticleSystems() {
        if (!this.config.enableParticles) return;

        // Particules de données flottantes
        const particleCount = this.config.particleCount;
        const geometry = new THREE.BufferGeometry();
        
        const positions = new Float32Array(particleCount * 3);
        const colors = new Float32Array(particleCount * 3);

        for (let i = 0; i < particleCount; i++) {
            const radius = Math.random() * 15 + 5;
            const theta = Math.random() * Math.PI * 2;
            const phi = Math.random() * Math.PI;

            positions[i * 3] = radius * Math.sin(phi) * Math.cos(theta);
            positions[i * 3 + 1] = radius * Math.cos(phi);
            positions[i * 3 + 2] = radius * Math.sin(phi) * Math.sin(theta);

            const colorIndex = Math.floor(Math.random() * 4);
            const particleColors = [
                [0.2, 0.6, 1.0], [1.0, 0.4, 0.2], [0.2, 0.8, 0.3], [1.0, 0.8, 0.0]
            ];

            colors[i * 3] = particleColors[colorIndex][0];
            colors[i * 3 + 1] = particleColors[colorIndex][1];
            colors[i * 3 + 2] = particleColors[colorIndex][2];
        }

        geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        geometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));

        const material = new THREE.ShaderMaterial({
            uniforms: { time: { value: 0 } },
            vertexShader: `
                varying vec3 vColor;
                uniform float time;
                void main() {
                    vColor = color;
                    vec4 mvPosition = modelViewMatrix * vec4(position, 1.0);
                    mvPosition.y += sin(time + position.x * 0.1) * 0.5;
                    gl_PointSize = 2.0 * (300.0 / -mvPosition.z);
                    gl_Position = projectionMatrix * mvPosition;
                }
            `,
            fragmentShader: `
                varying vec3 vColor;
                void main() {
                    float distanceToCenter = distance(gl_PointCoord, vec2(0.5));
                    float alpha = 1.0 - smoothstep(0.0, 0.5, distanceToCenter);
                    gl_FragColor = vec4(vColor, alpha * 0.8);
                }
            `,
            transparent: true,
            vertexColors: true,
            blending: THREE.AdditiveBlending
        });

        const particles = new THREE.Points(geometry, material);
        this.scene.add(particles);
        this.particleSystems.set('dataParticles', { mesh: particles, material });
    }

    async createHolographicCards() {
        if (!this.config.enableHolographicCards) return;

        const cardPositions = [
            { x: -6, y: 2, z: 0 }, { x: 6, y: 2, z: 0 },
            { x: 0, y: 2, z: -6 }, { x: 0, y: 2, z: 6 }
        ];

        const cardData = [
            { id: 'total-students', title: 'Étudiants', value: 1250, color: 0x3399ff },
            { id: 'completion-rate', title: 'Réussite', value: 85, color: 0x28a745 },
            { id: 'total-tps', title: 'TP Soumis', value: 456, color: 0xffc107 },
            { id: 'monthly-revenue', title: 'Revenus', value: 15750, color: 0xff6633 }
        ];

        for (let i = 0; i < cardPositions.length; i++) {
            const card = await this.createSingleHolographicCard(cardData[i], cardPositions[i]);
            this.dataCards.set(cardData[i].id, card);
        }
    }

    async createSingleHolographicCard(data, position) {
        const group = new THREE.Group();

        // Base holographique
        const cardGeometry = new THREE.PlaneGeometry(3, 2);
        const cardMaterial = new THREE.MeshPhongMaterial({
            color: data.color,
            transparent: true,
            opacity: 0.7,
            emissive: data.color,
            emissiveIntensity: 0.1,
            side: THREE.DoubleSide
        });

        const cardMesh = new THREE.Mesh(cardGeometry, cardMaterial);
        group.add(cardMesh);

        // Bordure lumineuse
        const borderGeometry = new THREE.EdgesGeometry(cardGeometry);
        const borderMaterial = new THREE.LineBasicMaterial({
            color: data.color,
            transparent: true,
            opacity: 0.8
        });
        const borderLines = new THREE.LineSegments(borderGeometry, borderMaterial);
        group.add(borderLines);

        group.position.set(position.x, position.y, position.z);
        group.userData = { ...data, originalPosition: position };

        this.scene.add(group);
        return group;
    }

    async setupInteractionSystem() {
        this.renderer.domElement.addEventListener('mousemove', (event) => {
            this.onMouseMove(event);
        });

        this.renderer.domElement.addEventListener('click', (event) => {
            this.onMouseClick(event);
        });

        window.addEventListener('resize', () => {
            this.onWindowResize();
        });
    }

    onMouseMove(event) {
        const rect = this.renderer.domElement.getBoundingClientRect();
        this.mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1;
        this.mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1;

        this.raycaster.setFromCamera(this.mouse, this.camera);
        const intersectableObjects = Array.from(this.dataCards.values());
        const intersects = this.raycaster.intersectObjects(intersectableObjects, true);

        if (intersects.length > 0) {
            const hoveredCard = intersects[0].object.parent;
            this.onCardHover(hoveredCard);
        } else {
            this.onCardUnhover();
        }
    }

    onMouseClick(event) {
        this.raycaster.setFromCamera(this.mouse, this.camera);
        const intersectableObjects = Array.from(this.dataCards.values());
        const intersects = this.raycaster.intersectObjects(intersectableObjects, true);

        if (intersects.length > 0) {
            const clickedCard = intersects[0].object.parent;
            this.onCardClick(clickedCard);
        }
    }

    onCardHover(card) {
        if (this.hoveredObject === card) return;

        if (this.hoveredObject) {
            this.resetCardHover(this.hoveredObject);
        }

        this.hoveredObject = card;
        card.scale.setScalar(1.2);
        card.position.y = card.userData.originalPosition.y + 0.5;
        this.renderer.domElement.style.cursor = 'pointer';
        this.emitEvent('card:hover', { cardId: card.userData.id });
    }

    resetCardHover(card) {
        card.scale.setScalar(1);
        card.position.y = card.userData.originalPosition.y;
    }

    onCardUnhover() {
        if (this.hoveredObject) {
            this.resetCardHover(this.hoveredObject);
            this.hoveredObject = null;
        }
        this.renderer.domElement.style.cursor = 'default';
    }

    onCardClick(card) {
        // Effet de pulsation
        const originalScale = card.scale.x;
        card.scale.setScalar(1.3);
        setTimeout(() => {
            card.scale.setScalar(originalScale);
        }, 200);

        this.emitEvent('card:click', { 
            cardId: card.userData.id,
            cardData: card.userData 
        });
    }

    onWindowResize() {
        const container = this.renderer.domElement.parentElement;
        const width = container.clientWidth;
        const height = container.clientHeight;

        this.camera.aspect = width / height;
        this.camera.updateProjectionMatrix();
        this.renderer.setSize(width, height);
    }

    async startRenderLoop() {
        const animate = () => {
            this.animationFrameId = requestAnimationFrame(animate);
            
            const elapsedTime = this.clock.getElapsedTime();
            
            if (this.controls) {
                this.controls.update();
            }
            
            this.updateParticles(elapsedTime);
            this.updateHolographicCards(elapsedTime);
            
            this.renderer.render(this.scene, this.camera);
        };
        
        animate();
    }

    updateParticles(time) {
        const dataParticles = this.particleSystems.get('dataParticles');
        if (dataParticles && dataParticles.material.uniforms) {
            dataParticles.material.uniforms.time.value = time;
        }
    }

    updateHolographicCards(time) {
        this.dataCards.forEach((card) => {
            const originalY = card.userData.originalPosition.y;
            if (card !== this.hoveredObject) {
                card.position.y = originalY + Math.sin(time + card.userData.originalPosition.x) * 0.1;
            }
            card.rotation.z = Math.sin(time * 0.5) * 0.02;
        });
    }

    updateStatistic(cardId, newValue, options = {}) {
        const card = this.dataCards.get(cardId);
        if (!card) return false;

        card.userData.value = newValue;
        
        // Effet visuel de mise à jour
        const { changeType = 'neutral' } = options;
        let pulseColor = 0xffffff;
        
        switch (changeType) {
            case 'positive': pulseColor = 0x28a745; break;
            case 'negative': pulseColor = 0xdc3545; break;
            default: pulseColor = 0x3399ff;
        }

        // Animation de pulsation
        const originalEmissive = card.children[0].material.emissiveIntensity;
        card.children[0].material.emissiveIntensity = 0.5;
        
        setTimeout(() => {
            card.children[0].material.emissiveIntensity = originalEmissive;
        }, 500);

        this.emitEvent('statistic:updated-3d', { cardId, newValue });
        return true;
    }

    emitEvent(eventName, detail = {}) {
        const event = new CustomEvent(eventName, { detail });
        document.dispatchEvent(event);
    }

    log(message, ...args) {
        if (this.config.debug) {
            console.log(`[3D Visualizer] ${message}`, ...args);
        }
    }

    destroy() {
        if (this.animationFrameId) {
            cancelAnimationFrame(this.animationFrameId);
        }
        
        if (this.renderer) {
            this.renderer.dispose();
        }
        
        this.scene = null;
        this.camera = null;
        this.renderer = null;
    }
}

// Instance globale
window.Admin3DVisualizer = Admin3DVisualizer;
