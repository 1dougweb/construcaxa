<div>
    @if(empty($apiKey))
        <div class="bg-yellow-100 dark:bg-yellow-900/30 border border-yellow-400 dark:border-yellow-600 text-yellow-700 dark:text-yellow-300 px-4 py-3 rounded mb-4">
            <p class="font-semibold">Google Maps não configurado</p>
            <p>Configure a chave da API do Google Maps nas 
                <a href="{{ route('admin.settings') }}" class="underline text-yellow-800 dark:text-yellow-400">configurações do sistema</a>
                para visualizar o mapa das obras.
            </p>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Obras em Andamento</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ count($projects) }} obra(s) localizadas no mapa</p>
                </div>
            </div>
            
            <div id="google-map-container" 
                 class="h-96 w-full" 
                 style="min-height: 384px; width: 100%;"
                 data-projects="{{ json_encode($projects) }}" 
                 data-center-lat="{{ $mapCenter['lat'] }}" 
                 data-center-lng="{{ $mapCenter['lng'] }}"
                 data-project-count="{{ count($projects) }}">
            </div>
            
            <script>
                let map;
                let markers = [];

                // Função global para inicializar o mapa
                window.initGoogleMap = function() {
                    const container = document.getElementById('google-map-container');
                    if (!container) {
                        console.error('Container do mapa não encontrado!');
                        return;
                    }

                    // Verificar se a API do Google Maps está disponível
                    if (typeof google === 'undefined' || 
                        typeof google.maps === 'undefined' || 
                        typeof google.maps.Map === 'undefined') {
                        console.error('API do Google Maps não carregada completamente!');
                        // Tentar novamente após um delay
                        setTimeout(() => {
                            if (typeof google !== 'undefined' && 
                                typeof google.maps !== 'undefined' && 
                                typeof google.maps.Map !== 'undefined') {
                                window.initGoogleMap();
                            }
                        }, 500);
                        return;
                    }

                    try {
                        const projects = JSON.parse(container.dataset.projects);
                        const centerLat = parseFloat(container.dataset.centerLat);
                        const centerLng = parseFloat(container.dataset.centerLng);
                        const projectCount = parseInt(container.dataset.projectCount);

                        // Verificar se está em dark mode
                        const isDarkMode = document.documentElement.classList.contains('dark');
                        
                        // Estilos para dark mode do Google Maps
                        const darkMapStyles = [
                            { elementType: 'geometry', stylers: [{ color: '#242f3e' }] },
                            { elementType: 'labels.text.stroke', stylers: [{ color: '#242f3e' }] },
                            { elementType: 'labels.text.fill', stylers: [{ color: '#746855' }] },
                            {
                                featureType: 'administrative.locality',
                                elementType: 'labels.text.fill',
                                stylers: [{ color: '#d59563' }]
                            },
                            {
                                featureType: 'poi',
                                elementType: 'labels',
                                stylers: [{ visibility: 'off' }]
                            },
                            {
                                featureType: 'poi',
                                elementType: 'labels.text.fill',
                                stylers: [{ color: '#d59563' }]
                            },
                            {
                                featureType: 'poi.park',
                                elementType: 'geometry',
                                stylers: [{ color: '#263c3f' }]
                            },
                            {
                                featureType: 'poi.park',
                                elementType: 'labels.text.fill',
                                stylers: [{ color: '#6b9a76' }]
                            },
                            {
                                featureType: 'road',
                                elementType: 'geometry',
                                stylers: [{ color: '#38414e' }]
                            },
                            {
                                featureType: 'road',
                                elementType: 'geometry.stroke',
                                stylers: [{ color: '#212a37' }]
                            },
                            {
                                featureType: 'road',
                                elementType: 'labels.text.fill',
                                stylers: [{ color: '#9ca5b3' }]
                            },
                            {
                                featureType: 'road.highway',
                                elementType: 'geometry',
                                stylers: [{ color: '#746855' }]
                            },
                            {
                                featureType: 'road.highway',
                                elementType: 'geometry.stroke',
                                stylers: [{ color: '#1f2835' }]
                            },
                            {
                                featureType: 'road.highway',
                                elementType: 'labels.text.fill',
                                stylers: [{ color: '#f3d19c' }]
                            },
                            {
                                featureType: 'transit',
                                elementType: 'geometry',
                                stylers: [{ color: '#2f3948' }]
                            },
                            {
                                featureType: 'transit.station',
                                elementType: 'labels.text.fill',
                                stylers: [{ color: '#d59563' }]
                            },
                            {
                                featureType: 'water',
                                elementType: 'geometry',
                                stylers: [{ color: '#17263c' }]
                            },
                            {
                                featureType: 'water',
                                elementType: 'labels.text.fill',
                                stylers: [{ color: '#515c6d' }]
                            },
                            {
                                featureType: 'water',
                                elementType: 'labels.text.stroke',
                                stylers: [{ color: '#17263c' }]
                            }
                        ];
                        
                        const lightMapStyles = [
                            {
                                featureType: 'poi',
                                elementType: 'labels',
                                stylers: [{ visibility: 'off' }]
                            }
                        ];
                        
                        // Configuração do mapa com zoom aumentado
                        map = new google.maps.Map(container, {
                            zoom: projectCount > 0 ? 14 : 8,
                            center: { lat: centerLat, lng: centerLng },
                            mapTypeId: 'roadmap',
                            styles: isDarkMode ? darkMapStyles : lightMapStyles
                        });
                        
                        // Forçar redimensionamento do mapa
                        setTimeout(() => {
                            google.maps.event.trigger(map, 'resize');
                        }, 100);

                        // Adicionar marcadores
                        addMarkers(projects);

                        // Configurar observer após o mapa ser criado
                        setTimeout(() => {
                            setupObserver();
                        }, 500);

                    } catch (error) {
                        console.error('Erro ao inicializar o mapa:', error);
                    }
                };

                // Função para adicionar marcadores
                function addMarkers(projects) {
                    if (!map) {
                        console.warn('Mapa não inicializado ainda');
                        return;
                    }

                    // Limpar marcadores existentes
                    markers.forEach(m => {
                        if (m.marker) {
                            m.marker.setMap(null);
                        }
                        if (m.infoWindow) {
                            m.infoWindow.close();
                        }
                    });
                    markers = [];

                    // Adicionar novos marcadores
                    if (projects && Array.isArray(projects) && projects.length > 0) {
                        projects.forEach(function(project) {
                            // Usar Marker tradicional (mais compatível)
                            const marker = new google.maps.Marker({
                                position: { lat: project.latitude, lng: project.longitude },
                                map: map,
                                title: project.name,
                                icon: {
                                    url: getMarkerIcon(project.status),
                                    scaledSize: new google.maps.Size(48, 48),
                                    origin: new google.maps.Point(0, 0),
                                    anchor: new google.maps.Point(24, 48)
                                }
                            });

                            // Verificar dark mode para InfoWindow
                            const isDarkMode = document.documentElement.classList.contains('dark');
                            const bgColor = isDarkMode ? 'bg-gray-800' : 'bg-white';
                            const textColor = isDarkMode ? 'text-gray-100' : 'text-gray-900';
                            const textSecondary = isDarkMode ? 'text-gray-400' : 'text-gray-600';
                            const buttonBg = isDarkMode ? 'bg-indigo-700 hover:bg-indigo-600' : 'bg-indigo-600 hover:bg-indigo-700';
                            
                            // Info window para cada marcador
                            const infoWindow = new google.maps.InfoWindow({
                                content: `
                                    <div class="p-2 max-w-xs ${bgColor}">
                                        <h4 class="font-semibold ${textColor}">${project.name}</h4>
                                        <p class="text-sm ${textSecondary}">Código: ${project.code}</p>
                                        ${project.os_number ? `<p class="text-sm ${textSecondary}">OS: ${project.os_number}</p>` : ''}
                                        <p class="text-sm ${textSecondary}">Cliente: ${project.client_name}</p>
                                        <p class="text-sm ${textSecondary}">Progresso: ${project.progress_percentage}%</p>
                                        <p class="text-sm ${textSecondary} mb-2">${project.address}</p>
                                        <a href="${project.url}" 
                                           class="inline-block ${buttonBg} text-white px-3 py-1 rounded text-sm transition-colors">
                                            Ver Detalhes
                                        </a>
                                    </div>
                                `
                            });

                            marker.addListener('click', function() {
                                // Fechar outras info windows
                                markers.forEach(m => m.infoWindow && m.infoWindow.close());
                                infoWindow.open(map, marker);
                            });

                            markers.push({ marker: marker, infoWindow: infoWindow, project: project });
                        });

                        // Ajustar zoom para mostrar todos os marcadores
                        if (projects.length > 1) {
                            const bounds = new google.maps.LatLngBounds();
                            projects.forEach(function(project) {
                                bounds.extend(new google.maps.LatLng(project.latitude, project.longitude));
                            });
                            map.fitBounds(bounds);
                            // Aumentar um pouco o zoom após ajustar os bounds
                            setTimeout(() => {
                                const currentZoom = map.getZoom();
                                map.setZoom(Math.min(currentZoom + 2, 16));
                            }, 500);
                        } else if (projects.length === 1) {
                            map.setCenter({ lat: projects[0].latitude, lng: projects[0].longitude });
                            map.setZoom(14);
                        }
                    }
                }

                // Função para atualizar o mapa com novos dados
                function updateMapFromContainer() {
                    const container = document.getElementById('google-map-container');
                    if (!container || !map) {
                        return;
                    }

                    try {
                        const projectsJson = container.dataset.projects;
                        if (!projectsJson) {
                            return;
                        }

                        const projects = JSON.parse(projectsJson);
                        const centerLat = parseFloat(container.dataset.centerLat);
                        const centerLng = parseFloat(container.dataset.centerLng);
                        const projectCount = parseInt(container.dataset.projectCount) || 0;

                        // Atualizar marcadores primeiro
                        addMarkers(Array.isArray(projects) ? projects : []);

                        // Ajustar centro e zoom
                        if (projectCount > 0 && Array.isArray(projects) && projects.length > 0) {
                            if (projects.length === 1) {
                                // Um único projeto: centralizar nele
                                map.setCenter({ lat: projects[0].latitude, lng: projects[0].longitude });
                                map.setZoom(14);
                            } else {
                                // Múltiplos projetos: ajustar bounds
                                const bounds = new google.maps.LatLngBounds();
                                projects.forEach(function(project) {
                                    bounds.extend(new google.maps.LatLng(project.latitude, project.longitude));
                                });
                                map.fitBounds(bounds);
                                setTimeout(() => {
                                    const currentZoom = map.getZoom();
                                    map.setZoom(Math.min(currentZoom + 2, 16));
                                }, 500);
                            }
                        } else if (centerLat && centerLng && !isNaN(centerLat) && !isNaN(centerLng)) {
                            // Sem projetos: usar centro padrão
                            map.setCenter({ lat: centerLat, lng: centerLng });
                            map.setZoom(8);
                        }

                        // Forçar redimensionamento para garantir que o mapa seja visível
                        setTimeout(() => {
                            google.maps.event.trigger(map, 'resize');
                        }, 100);
                    } catch (error) {
                        console.error('Erro ao atualizar mapa:', error);
                    }
                }

                // Listener para atualização do mapa via Livewire
                document.addEventListener('livewire:init', () => {
                    // Listener para quando o Livewire atualizar o DOM (Livewire 3)
                    if (typeof Livewire !== 'undefined' && Livewire.hook) {
                        try {
                            Livewire.hook('morph.updated', ({ el, component }) => {
                                if (el && (el.id === 'google-map-container' || el.closest('#google-map-container'))) {
                                    setTimeout(updateMapFromContainer, 150);
                                }
                            });
                        } catch (e) {
                            // Hook não disponível, usar fallback
                        }
                    }

                    // Listener para eventos customizados (fallback)
                    Livewire.on('updateMap', (data) => {
                        setTimeout(updateMapFromContainer, 150);
                    });
                });

                // Listener adicional para Livewire 2 (compatibilidade)
                if (typeof Livewire !== 'undefined') {
                    document.addEventListener('livewire:update', () => {
                        setTimeout(updateMapFromContainer, 200);
                    });
                }

                // Listener adicional para mudanças no DOM (quando Livewire atualiza)
                let observer = null;
                let observerConfigured = false;

                function setupObserver() {
                    if (observerConfigured) {
                        return; // Já configurado
                    }

                    const container = document.getElementById('google-map-container');
                    if (!container || !map) {
                        return;
                    }

                    if (!observer) {
                        observer = new MutationObserver((mutations) => {
                            mutations.forEach((mutation) => {
                                if (mutation.type === 'attributes' && 
                                    mutation.target.id === 'google-map-container' &&
                                    (mutation.attributeName === 'data-projects' || 
                                     mutation.attributeName === 'data-center-lat' ||
                                     mutation.attributeName === 'data-center-lng' ||
                                     mutation.attributeName === 'data-project-count')) {
                                    setTimeout(updateMapFromContainer, 100);
                                }
                            });
                        });
                    }

                    observer.observe(container, {
                        attributes: true,
                        attributeFilter: ['data-projects', 'data-center-lat', 'data-center-lng', 'data-project-count']
                    });

                    observerConfigured = true;
                }

                function getMarkerIcon(status) {
                    const colors = {
                        'in_progress': '#10B981', // Verde
                        'planned': '#F59E0B',     // Amarelo
                        'paused': '#EF4444',      // Vermelho
                        'completed': '#6B7280',   // Cinza
                        'cancelled': '#374151'    // Cinza escuro
                    };
                    
                    const color = colors[status] || '#6B7280';
                    
                    return `data:image/svg+xml;charset=UTF-8,${encodeURIComponent(`
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="${color}">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                    `)}`;
                }


                // Função para aguardar o carregamento da API
                function waitForGoogleMaps() {
                    if (typeof google !== 'undefined' && 
                        typeof google.maps !== 'undefined' && 
                        typeof google.maps.Map !== 'undefined') {
                        window.initGoogleMap();
                    } else {
                        setTimeout(waitForGoogleMaps, 100);
                    }
                }

                // Função para atualizar o tema do mapa quando mudar
                function updateMapTheme() {
                    if (!map) return;
                    
                    const isDarkMode = document.documentElement.classList.contains('dark');
                    const darkMapStyles = [
                        { elementType: 'geometry', stylers: [{ color: '#242f3e' }] },
                        { elementType: 'labels.text.stroke', stylers: [{ color: '#242f3e' }] },
                        { elementType: 'labels.text.fill', stylers: [{ color: '#746855' }] },
                        {
                            featureType: 'administrative.locality',
                            elementType: 'labels.text.fill',
                            stylers: [{ color: '#d59563' }]
                        },
                        {
                            featureType: 'poi',
                            elementType: 'labels',
                            stylers: [{ visibility: 'off' }]
                        },
                        {
                            featureType: 'poi',
                            elementType: 'labels.text.fill',
                            stylers: [{ color: '#d59563' }]
                        },
                        {
                            featureType: 'poi.park',
                            elementType: 'geometry',
                            stylers: [{ color: '#263c3f' }]
                        },
                        {
                            featureType: 'poi.park',
                            elementType: 'labels.text.fill',
                            stylers: [{ color: '#6b9a76' }]
                        },
                        {
                            featureType: 'road',
                            elementType: 'geometry',
                            stylers: [{ color: '#38414e' }]
                        },
                        {
                            featureType: 'road',
                            elementType: 'geometry.stroke',
                            stylers: [{ color: '#212a37' }]
                        },
                        {
                            featureType: 'road',
                            elementType: 'labels.text.fill',
                            stylers: [{ color: '#9ca5b3' }]
                        },
                        {
                            featureType: 'road.highway',
                            elementType: 'geometry',
                            stylers: [{ color: '#746855' }]
                        },
                        {
                            featureType: 'road.highway',
                            elementType: 'geometry.stroke',
                            stylers: [{ color: '#1f2835' }]
                        },
                        {
                            featureType: 'road.highway',
                            elementType: 'labels.text.fill',
                            stylers: [{ color: '#f3d19c' }]
                        },
                        {
                            featureType: 'transit',
                            elementType: 'geometry',
                            stylers: [{ color: '#2f3948' }]
                        },
                        {
                            featureType: 'transit.station',
                            elementType: 'labels.text.fill',
                            stylers: [{ color: '#d59563' }]
                        },
                        {
                            featureType: 'water',
                            elementType: 'geometry',
                            stylers: [{ color: '#17263c' }]
                        },
                        {
                            featureType: 'water',
                            elementType: 'labels.text.fill',
                            stylers: [{ color: '#515c6d' }]
                        },
                        {
                            featureType: 'water',
                            elementType: 'labels.text.stroke',
                            stylers: [{ color: '#17263c' }]
                        }
                    ];
                    
                    const lightMapStyles = [
                        {
                            featureType: 'poi',
                            elementType: 'labels',
                            stylers: [{ visibility: 'off' }]
                        }
                    ];
                    
                    map.setOptions({
                        styles: isDarkMode ? darkMapStyles : lightMapStyles
                    });
                }
                
                // Observer para mudanças no tema
                let themeObserver = null;
                function setupThemeObserver() {
                    if (themeObserver) return;
                    
                    themeObserver = new MutationObserver(() => {
                        updateMapTheme();
                        // Atualizar InfoWindows abertas
                        markers.forEach(m => {
                            if (m.infoWindow && m.project) {
                                // Verificar se a InfoWindow está aberta
                                const isOpen = m.infoWindow.getMap() !== null;
                                if (isOpen) {
                                    const marker = m.marker;
                                    const project = m.project;
                                    
                                    // Recriar InfoWindow com novo tema
                                    const isDarkMode = document.documentElement.classList.contains('dark');
                                    const bgColor = isDarkMode ? 'bg-gray-800' : 'bg-white';
                                    const textColor = isDarkMode ? 'text-gray-100' : 'text-gray-900';
                                    const textSecondary = isDarkMode ? 'text-gray-400' : 'text-gray-600';
                                    const buttonBg = isDarkMode ? 'bg-indigo-700 hover:bg-indigo-600' : 'bg-indigo-600 hover:bg-indigo-700';
                                    
                                    const newInfoWindow = new google.maps.InfoWindow({
                                        content: `
                                            <div class="p-2 max-w-xs ${bgColor}">
                                                <h4 class="font-semibold ${textColor}">${project.name}</h4>
                                                <p class="text-sm ${textSecondary}">Código: ${project.code}</p>
                                                ${project.os_number ? `<p class="text-sm ${textSecondary}">OS: ${project.os_number}</p>` : ''}
                                                <p class="text-sm ${textSecondary}">Cliente: ${project.client_name}</p>
                                                <p class="text-sm ${textSecondary}">Progresso: ${project.progress_percentage}%</p>
                                                <p class="text-sm ${textSecondary} mb-2">${project.address}</p>
                                                <a href="${project.url}" 
                                                   class="inline-block ${buttonBg} text-white px-3 py-1 rounded text-sm transition-colors">
                                                    Ver Detalhes
                                                </a>
                                            </div>
                                        `
                                    });
                                    
                                    m.infoWindow.close();
                                    m.infoWindow = newInfoWindow;
                                    newInfoWindow.open(map, marker);
                                }
                            }
                        });
                    });
                    
                    themeObserver.observe(document.documentElement, {
                        attributes: true,
                        attributeFilter: ['class']
                    });
                }
                
                // Inicializar quando o documento estiver pronto
                document.addEventListener('DOMContentLoaded', function() {
                    waitForGoogleMaps();
                    
                    // Configurar observer de tema após o mapa ser criado
                    setTimeout(() => {
                        setupThemeObserver();
                    }, 1000);
                });
            </script>
            
            <script async defer 
                    src="https://maps.googleapis.com/maps/api/js?key={{ $apiKey }}&libraries=geometry"
                    onload="waitForGoogleMaps()">
            </script>
        </div>
    @endif
</div>
