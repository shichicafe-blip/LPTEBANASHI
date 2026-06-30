/**
 * TEBANASHI LP - メインJavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // アニメーション対象の要素を設定
    const animationTargets = [
        // 提案セクション
        { selector: '#proposal .container > img', class: 'animate-on-scroll' },
        { selector: '.proposal-box', class: 'animate-on-scroll' },
        { selector: '.proposal-list', class: 'stagger-animation' },
        
        // 問題解決セクション
        { selector: '.problem-title', class: 'animate-on-scroll' },
        { selector: '.problem-subtitle-box', class: 'animate-on-scroll' },
        { selector: '.animate-problem', class: 'animate-on-scroll', stagger: true },
        { selector: '.problem-conclusion', class: 'animate-on-scroll' },
        
        // CTAセクション
        { selector: '#cta h3', class: 'animate-on-scroll' },
        { selector: '.btn-cta', class: 'animate-scale' },
        
        // 回答セクション
        { selector: '.answer-title', class: 'animate-on-scroll' },
        { selector: '.answer-description', class: 'animate-on-scroll' },
        
        // システム紹介セクション
        { selector: '.system-intro-title', class: 'animate-on-scroll' },
        { selector: '.system-intro-image', class: 'animate-scale' },
        
        // 機能紹介セクション
        { selector: '.features-title', class: 'animate-on-scroll' },
        { selector: '.features-description', class: 'animate-on-scroll' },
        { selector: '.features-subtitle', class: 'animate-on-scroll' },
        { selector: '.feature-card', class: 'animate-on-scroll', stagger: true },
        
        // お客様の声セクション
        { selector: '.voice-title', class: 'animate-on-scroll' },
        { selector: '.voice-card', class: 'animate-on-scroll', stagger: true },
        
        // 選ばれる理由セクション
        { selector: '.reason-title', class: 'animate-on-scroll' },
        { selector: '.reason-item', class: 'animate-on-scroll', stagger: true },
        { selector: '.achievement-ratio-box', class: 'animate-on-scroll' },
        
        // 料金プランセクション
        { selector: '.plan-title', class: 'animate-on-scroll' },
        { selector: '.plan-description', class: 'animate-on-scroll' },
        { selector: '.plan-card', class: 'animate-on-scroll', stagger: true },
        { selector: '.plan-benefit', class: 'animate-on-scroll' },
        
        // 導入の流れセクション
        { selector: '.intro-flow-title', class: 'animate-on-scroll' },
        { selector: '.intro-flow-content', class: 'animate-on-scroll' },
        
        // FAQセクション
        { selector: '.faq-title', class: 'animate-on-scroll' },
        { selector: '.faq-item', class: 'animate-on-scroll', stagger: true },
        
        // 実績セクション
        { selector: '.achievement-title', class: 'animate-on-scroll' },
        { selector: '.achievement-card', class: 'animate-on-scroll', stagger: true },
        
        // お問い合わせセクション
        { selector: '.contact-title', class: 'animate-on-scroll' },
        { selector: '.contact-description', class: 'animate-on-scroll' },
        { selector: '.contact-left', class: 'animate-from-left' },
        { selector: '.contact-right', class: 'animate-from-right' }
    ];
    
    // 要素にアニメーションクラスを追加
    animationTargets.forEach(target => {
        const elements = document.querySelectorAll(target.selector);
        elements.forEach((el, index) => {
            el.classList.add(target.class);
            if (target.stagger) {
                el.style.transitionDelay = (index * 0.1) + 's';
            }
        });
    });
    
    // Intersection Observer の設定
    const observerOptions = {
        root: null,
        rootMargin: '0px 0px -50px 0px',
        threshold: 0.1
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animated');
                // 一度アニメーションしたら監視を解除（パフォーマンス向上）
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // アニメーション対象の要素を監視
    const animatedElements = document.querySelectorAll(
        '.animate-on-scroll, .animate-fade-in, .animate-from-left, .animate-from-right, .animate-scale, .stagger-animation, .animate-problem'
    );
    animatedElements.forEach(el => observer.observe(el));
    
    // ヘッダーのスクロール時の背景変更
    const header = document.querySelector('.header');
    let lastScrollY = 0;
    
    window.addEventListener('scroll', () => {
        const currentScrollY = window.scrollY;
        
        if (currentScrollY > 100) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
        
        lastScrollY = currentScrollY;
    }, { passive: true });
    
    // スムーズスクロール（アンカーリンク用）
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href === '#') return;
            
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                const headerHeight = header.offsetHeight;
                const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // 数字のカウントアップアニメーション（オプション）
    function animateCounter(element, target, duration = 2000) {
        let start = 0;
        const increment = target / (duration / 16);
        
        const updateCounter = () => {
            start += increment;
            if (start < target) {
                element.textContent = Math.floor(start);
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = target;
            }
        };
        
        updateCounter();
    }
    
    // パララックス効果（ヒーローセクション - オプション）
    const heroSection = document.querySelector('.hero');
    if (heroSection) {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const heroHeight = heroSection.offsetHeight;
            
            if (scrolled < heroHeight) {
                heroSection.style.backgroundPositionY = scrolled * 0.5 + 'px';
            }
        }, { passive: true });
    }
    
    // ライトボックス機能
    const lightbox = document.getElementById('lightbox');
    const lightboxImage = lightbox.querySelector('.lightbox-image');
    const lightboxClose = lightbox.querySelector('.lightbox-close');
    const featureImages = document.querySelectorAll('.feature-card-image');
    
    // 画像クリックでライトボックスを開く
    featureImages.forEach(imageContainer => {
        imageContainer.addEventListener('click', function() {
            const img = this.querySelector('img');
            if (img) {
                lightboxImage.src = img.src;
                lightboxImage.alt = img.alt;
                lightbox.classList.add('active');
                document.body.classList.add('lightbox-open');
            }
        });
    });
    
    // 閉じるボタンでライトボックスを閉じる
    lightboxClose.addEventListener('click', function() {
        lightbox.classList.remove('active');
        document.body.classList.remove('lightbox-open');
    });
    
    // 背景クリックでライトボックスを閉じる
    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox) {
            lightbox.classList.remove('active');
            document.body.classList.remove('lightbox-open');
        }
    });
    
    // ESCキーでライトボックスを閉じる
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && lightbox.classList.contains('active')) {
            lightbox.classList.remove('active');
            document.body.classList.remove('lightbox-open');
        }
    });

    // タブ切り替え機能
    const reasonTabs = document.querySelectorAll('.reason-tab');
    const reasonContents = document.querySelectorAll('.reason-tab-content');
    
    reasonTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetId = this.getAttribute('data-tab');
            
            // 全てのタブとコンテンツからactiveを削除
            reasonTabs.forEach(t => t.classList.remove('active'));
            reasonContents.forEach(c => c.classList.remove('active'));
            
            // クリックされたタブと対応するコンテンツにactiveを追加
            this.classList.add('active');
            document.getElementById(targetId).classList.add('active');
        });
    });
    
    // 一文字ずつふわっと出現するアニメーション
    const charAnimationTargets = [
        '.proposal-box-title',
        '.problem-title',
        '.answer-title',
        '.features-title'
    ];
    
    // テキストを一文字ずつspanで囲む関数
    function wrapCharsInSpan(element) {
        // 既に処理済みの場合はスキップ
        if (element.classList.contains('char-wrapped')) return;
        
        const nodes = Array.from(element.childNodes);
        let charIndex = 0;
        
        nodes.forEach(node => {
            if (node.nodeType === Node.TEXT_NODE) {
                // テキストノードを一文字ずつspanで囲む
                const text = node.textContent;
                const fragment = document.createDocumentFragment();
                
                for (let i = 0; i < text.length; i++) {
                    const char = text[i];
                    if (char === ' ' || char === '\n' || char === '\t') {
                        fragment.appendChild(document.createTextNode(char));
                    } else {
                        const span = document.createElement('span');
                        span.className = 'char-animate';
                        span.style.setProperty('--char-index', charIndex);
                        span.textContent = char;
                        fragment.appendChild(span);
                        charIndex++;
                    }
                }
                node.parentNode.replaceChild(fragment, node);
            } else if (node.nodeType === Node.ELEMENT_NODE) {
                // 要素ノード（imgなど）はそのまま維持し、インデックスだけ進める
                node.classList.add('char-animate');
                node.style.setProperty('--char-index', charIndex);
                charIndex++;
            }
        });
        
        element.classList.add('char-wrapped');
    }
    
    // 一文字アニメーション用のIntersection Observer
    const charObserverOptions = {
        root: null,
        rootMargin: '0px 0px -100px 0px',
        threshold: 0.1
    };
    
    const charObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('char-animated');
                charObserver.unobserve(entry.target);
            }
        });
    }, charObserverOptions);
    
    // 対象要素にアニメーションを設定
    charAnimationTargets.forEach(selector => {
        const elements = document.querySelectorAll(selector);
        elements.forEach(el => {
            wrapCharsInSpan(el);
            charObserver.observe(el);
        });
    });

    // 動画モーダル
    initVideoModal();
});

/**
 * 動画モーダルの初期化
 */
function initVideoModal() {
    const modal = document.getElementById('videoModal');
    const video = document.getElementById('modalVideo');
    const overlay = modal.querySelector('.video-modal-overlay');
    const closeBtn = modal.querySelector('.video-modal-close');
    const demoBtn = document.querySelector('.btn-demo');
    const thumbnail = document.getElementById('videoThumbnail');
    const thumbnailClose = thumbnail ? thumbnail.querySelector('.video-thumbnail-close') : null;
    const thumbnailOverlay = thumbnail ? thumbnail.querySelector('.video-thumbnail-overlay') : null;
    
    function openModal(e) {
        e.preventDefault();
        e.stopPropagation();
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        video.play();
    }
    
    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
        video.pause();
        video.currentTime = 0;
    }
    
    function hideThumbnail(e) {
        e.preventDefault();
        e.stopPropagation();
        thumbnail.classList.add('hidden');
    }
    
    if (demoBtn) {
        demoBtn.addEventListener('click', openModal);
    }
    
    if (thumbnailOverlay) {
        thumbnailOverlay.addEventListener('click', openModal);
    }
    
    if (thumbnailClose) {
        thumbnailClose.addEventListener('click', hideThumbnail);
    }
    
    closeBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', closeModal);
    
    // ESCキーで閉じる
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });
}
