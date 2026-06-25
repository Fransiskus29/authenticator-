---
name: Fortress Protocol
colors:
  surface: '#f8f9ff'
  surface-dim: '#cbdbf5'
  surface-bright: '#f8f9ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#eff4ff'
  surface-container: '#e5eeff'
  surface-container-high: '#dce9ff'
  surface-container-highest: '#d3e4fe'
  on-surface: '#0b1c30'
  on-surface-variant: '#464555'
  inverse-surface: '#213145'
  inverse-on-surface: '#eaf1ff'
  outline: '#777587'
  outline-variant: '#c7c4d8'
  surface-tint: '#4d44e3'
  primary: '#3525cd'
  on-primary: '#ffffff'
  primary-container: '#4f46e5'
  on-primary-container: '#dad7ff'
  inverse-primary: '#c3c0ff'
  secondary: '#006c49'
  on-secondary: '#ffffff'
  secondary-container: '#6cf8bb'
  on-secondary-container: '#00714d'
  tertiary: '#960014'
  on-tertiary: '#ffffff'
  tertiary-container: '#bc1d25'
  on-tertiary-container: '#ffd0cc'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#e2dfff'
  primary-fixed-dim: '#c3c0ff'
  on-primary-fixed: '#0f0069'
  on-primary-fixed-variant: '#3323cc'
  secondary-fixed: '#6ffbbe'
  secondary-fixed-dim: '#4edea3'
  on-secondary-fixed: '#002113'
  on-secondary-fixed-variant: '#005236'
  tertiary-fixed: '#ffdad7'
  tertiary-fixed-dim: '#ffb3ad'
  on-tertiary-fixed: '#410004'
  on-tertiary-fixed-variant: '#930013'
  background: '#f8f9ff'
  on-background: '#0b1c30'
  surface-variant: '#d3e4fe'
typography:
  headline-lg:
    fontFamily: Inter
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
    letterSpacing: -0.02em
  headline-md:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
    letterSpacing: -0.01em
  otp-display:
    fontFamily: JetBrains Mono
    fontSize: 36px
    fontWeight: '600'
    lineHeight: 44px
    letterSpacing: 0.1em
  body-md:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  label-sm:
    fontFamily: Inter
    fontSize: 13px
    fontWeight: '500'
    lineHeight: 18px
  code-sm:
    fontFamily: JetBrains Mono
    fontSize: 14px
    fontWeight: '500'
    lineHeight: 20px
rounded:
  sm: 0.125rem
  DEFAULT: 0.25rem
  md: 0.375rem
  lg: 0.5rem
  xl: 0.75rem
  full: 9999px
spacing:
  base: 4px
  xs: 8px
  sm: 16px
  md: 24px
  lg: 32px
  xl: 48px
  container-max: 1200px
  gutter: 20px
---

## Brand & Style
The design system is engineered for high-stakes security environments where clarity and trust are paramount. The brand personality is professional, vigilant, and precise, targeting users who prioritize data integrity and seamless authentication workflows. 

The aesthetic is a hybrid of **Corporate Modern** and **Functional Minimalism**. It utilizes heavy whitespace to reduce cognitive load during time-sensitive tasks (like entering a 6-digit code) and employs subtle technical cues—such as monospaced digits and micro-interactions—to reinforce the feeling of a secure, encrypted environment. The UI should evoke a sense of "calm authority," ensuring the user feels in control of their digital identity.

## Colors
The palette is rooted in a "Security-First" logic. **Indigo (#4F46E5)** serves as the primary brand anchor, representing stability and intelligence. 

Functional state colors are the primary communicators in this design system:
- **Success Green (#10B981):** Used for active, valid authentication codes and secure connection states.
- **Warning Red (#EF4444):** Reserved for codes nearing expiration (last 5 seconds) and critical security alerts.
- **Neutral Grays:** A sophisticated range of cool grays derived from Slate (#64748B) ensures the interface feels balanced and reduces visual vibration.

The default mode is **Light**, utilizing a clean #F8FAFC background to maintain high contrast, though the system is architected to support a high-contrast "Midnight" dark mode for low-light environments.

## Typography
The typography strategy prioritizes legibility and technical precision. **Inter** is the workhorse font for all UI labels, navigation, and body copy, chosen for its exceptional readability on high-density screens.

For the core functional element—the 6-digit OTP code—the system utilizes **JetBrains Mono**. This monospaced choice prevents character jumping during countdowns and ensures each digit is distinct (e.g., distinguishing '1', 'l', and 'I'). 

- **Display Scale:** Use `headline-lg` for primary view titles (e.g., "All Accounts").
- **Authentication Scale:** `otp-display` is exclusively for the 6-digit codes to ensure they are the most prominent element on the screen.
- **Micro-copy:** `code-sm` is used for recovery keys and encrypted hashes.

## Layout & Spacing
The design system employs a **Fixed Grid** philosophy for desktop (centered 1200px container) and a **Fluid Fluid** approach for mobile devices. A 4px baseline grid ensures vertical rhythm across all components.

- **Desktop:** A minimalist sidebar (280px) houses navigation and account categories, with a primary content area for account cards.
- **Mobile:** A single-column list view with 16px (sm) horizontal margins. 
- **Account Cards:** Use a standard 24px (md) padding to maintain a spacious, professional feel. 
- **Rhythm:** Elements within a card (e.g., Service Name vs. OTP Code) should use 8px (xs) spacing, while the gap between separate cards should be 16px (sm).

## Elevation & Depth
Hierarchy is established through **Tonal Layers** and **Low-Contrast Outlines** rather than aggressive shadows. This reinforces the "Secure/Solid" brand narrative.

- **Level 0 (Background):** #F8FAFC - The base canvas.
- **Level 1 (Cards):** White (#FFFFFF) with a 1px border in #E2E8F0. No shadow is used in the default state to keep the UI "flat" and accessible.
- **Level 2 (Interaction):** When a card is hovered or focused, a soft, ambient shadow (0px 4px 12px rgba(0, 0, 0, 0.05)) and a 1px border in the primary color (#4F46E5) are applied.
- **Feedback:** Progress rings for code expiration should be visually "recessed" using a subtle inner-stroke effect to distinguish them from actionable buttons.

## Shapes
The shape language is **Soft (Level 1)**. Standard UI components like input fields and buttons use a 0.25rem (4px) radius. This creates a professional, disciplined appearance that feels modern without becoming overly "playful" or "consumer-soft."

- **Large Components (Cards):** Use `rounded-lg` (8px) to define the primary account containers.
- **System Icons:** Should follow a 2px stroke weight with slight rounding on terminals to match the font geometry.

## Components
Consistent component behavior is critical for user confidence in a security app.

- **Account Cards:** Must feature a service icon (e.g., Google, GitHub) on the left, followed by account details. The 6-digit code is right-aligned or centered, accompanied by a **Circular Progress Ring** that depletes as the code nears expiration.
- **Buttons:** 
  - *Primary:* Solid Indigo (#4F46E5) with white text.
  - *Add Account:* A prominent Floating Action Button (FAB) on mobile or a high-contrast primary button in the sidebar on desktop.
- **OTP Input:** When manually entering a code, use 6 individual boxes with an active border highlight on the current digit.
- **Status Indicators:** A small "Encrypted" badge (lock icon + text) should appear in account headers to reassure users.
- **Progress Rings:** Transitions from Green (#10B981) to Red (#EF4444) when the timer hits the 25% threshold (7.5 seconds for a 30-second TOTP).
- **Copy-to-Clipboard:** A subtle "tap-to-copy" interaction on the account card with a brief "Copied!" toast notification using the `label-sm` typography.