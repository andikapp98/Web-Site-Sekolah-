# 🎨 Visual Styling Guide

## Component Styling Reference

### 1. Welcome Section
```css
Background: linear-gradient(135deg, #2e7d32 0%, #43a047 100%)
Text Color: white
Border Radius: 20px
Padding: 2.5rem
Box Shadow: 0 10px 30px rgba(46, 125, 50, 0.3)
```

**Elements:**
- Title: 2.5rem, bold (700)
- Subtitle: 1.3rem, opacity 0.95
- Date: 1rem, opacity 0.85
- Decorative circles with rgba backgrounds

---

### 2. Stat Cards
```css
Background: white
Border Radius: 20px
Padding: 1.75rem
Box Shadow: 0 4px 15px rgba(0, 0, 0, 0.08)
Hover: translateY(-8px) scale(1.02)
```

**Icon:**
- Size: 65px × 65px
- Border Radius: 16px
- Gradient backgrounds (different per card)
- Box Shadow: 0 4px 12px rgba(46, 125, 50, 0.3)

**Number:**
- Font Size: 2.8rem
- Font Weight: 700
- Color: #2e7d32

**Trend Badge:**
```css
Positive: linear-gradient(135deg, #e8f5e9, #c8e6c9)
Text: #2e7d32
Padding: 0.35rem 0.85rem
Border Radius: 20px
```

**Progress Bar:**
```css
Container: height 8px, background #e0e0e0
Fill: linear-gradient(90deg, #2e7d32, #66bb6a)
Border Radius: 10px
Animation: width 1s ease-in-out
```

---

### 3. Quick Action Cards
```css
Background: white
Border Radius: 16px
Padding: 2rem 1.5rem
Box Shadow: 0 3px 10px rgba(0, 0, 0, 0.08)
Border: 2px solid transparent
Hover Border: #2e7d32
```

**Icon Circle:**
```css
Size: 70px × 70px
Border Radius: 50%
Background: linear-gradient(135deg, #e8f5e9, #c8e6c9)
Color: #2e7d32

On Hover:
  Background: linear-gradient(135deg, #2e7d32, #43a047)
  Color: white
  Transform: scale(1.1) rotate(5deg)
```

**Label:**
- Font Weight: 600
- Font Size: 1.05rem
- Color: var(--text-color)

---

### 4. Activity Items
```css
Background: #f8fdf9
Border Left: 4px solid #2e7d32
Border Radius: 12px
Padding: 1rem
Hover: 
  Background: #e8f5e9
  Transform: translateX(5px)
```

**Icon Badge:**
```css
Size: 45px × 45px
Border Radius: 12px
Font Size: 1.5rem (emoji)

Types:
  Success: linear-gradient(135deg, #e8f5e9, #c8e6c9)
  Info: linear-gradient(135deg, #e3f2fd, #bbdefb)
  Warning: linear-gradient(135deg, #fff3e0, #ffe0b2)
```

**Text:**
- Title: 0.95rem, bold (600)
- Time: 0.8rem, secondary color

---

### 5. Announcement Cards
```css
Background: #f8fdf9
Border: 2px solid var(--border-color)
Border Radius: 12px
Padding: 1.25rem
Hover:
  Background: #e8f5e9
  Border: #2e7d32
  Transform: translateY(-2px)
  Box Shadow: 0 4px 12px rgba(46, 125, 50, 0.15)
```

**Badge:**
```css
Background: linear-gradient(135deg, #2e7d32, #43a047)
Color: white
Padding: 0.4rem 0.8rem
Border Radius: 8px
Font Size: 0.75rem
Font Weight: 700
Text Transform: uppercase
Letter Spacing: 0.5px
```

**Content:**
- Title: 1.1rem, bold (700)
- Date: 0.9rem, emoji prefix
- Description: 0.9rem, line-height 1.5

---

### 6. Chart Cards
```css
Background: white
Border Radius: 16px
Padding: 1.75rem
Min Height: 380px
Box Shadow: 0 4px 15px rgba(0, 0, 0, 0.08)
```

**Title:**
```css
Font Size: 1.4rem
Font Weight: 700
Color: #2e7d32
Border Bottom: 3px solid var(--border-color)
Padding Bottom: 1rem
Display: flex with icon
```

---

## Color Variables

### CSS Custom Properties
```css
:root {
  /* Primary */
  --primary-color: #2e7d32;
  --primary-hover: #1b5e20;
  --secondary-color: #43a047;
  --accent-color: #66bb6a;
  
  /* Background */
  --background-color: #f1f8e9;
  --card-bg: #ffffff;
  
  /* Text */
  --text-color: #1b5e20;
  --text-secondary: #558b2f;
  
  /* Borders & Shadows */
  --border-color: #c5e1a5;
  --shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  --soft-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
}
```

---

## Gradient Presets

### Backgrounds
```css
Welcome Section: 
  linear-gradient(135deg, #2e7d32 0%, #43a047 100%)

Card Light:
  linear-gradient(135deg, #ffffff 0%, #f8fdf9 100%)

Page Background:
  linear-gradient(135deg, #f5f9f5 0%, #e8f5e9 100%)
```

### Icons
```css
Primary: linear-gradient(135deg, #2e7d32, #43a047)
Success: linear-gradient(135deg, #388e3c, #66bb6a)
Warning: linear-gradient(135deg, #558b2f, #8bc34a)
Info: linear-gradient(135deg, #1976d2, #42a5f5)
```

### Badges
```css
Positive Trend: linear-gradient(135deg, #e8f5e9, #c8e6c9)
Neutral: linear-gradient(135deg, #e3f2fd, #bbdefb)
Quick Action: linear-gradient(135deg, #e8f5e9, #c8e6c9)
```

---

## Animation Timings

```css
Fast: 0.2s
Normal: 0.3s
Slow: 0.4s
Progress: 1s
Entrance: 0.6s

Easing:
  ease-out (default)
  cubic-bezier(0.175, 0.885, 0.32, 1.275) (bounce)
```

---

## Spacing System

```css
xs:   0.5rem  (8px)
sm:   0.75rem (12px)
md:   1rem    (16px)
lg:   1.5rem  (24px)
xl:   2rem    (32px)
xxl:  2.5rem  (40px)
```

**Card Padding:**
- Small: 1.5rem
- Medium: 1.75rem
- Large: 2rem
- Extra: 2.5rem

**Margins:**
- Between cards: 1.5rem (mb-4)
- Between sections: 2.5rem
- Within elements: 0.5rem - 1rem

---

## Border Radius System

```css
Small: 8px   (badges, small buttons)
Medium: 12px (activity items, inputs)
Large: 16px  (cards, quick actions)
XLarge: 20px (main cards, welcome section)
Circle: 50%  (icon containers)
```

---

## Box Shadow Levels

```css
Level 1 (Resting):
  0 2px 8px rgba(0, 0, 0, 0.1)

Level 2 (Cards):
  0 4px 15px rgba(0, 0, 0, 0.08)

Level 3 (Hover):
  0 8px 25px rgba(46, 125, 50, 0.2)

Level 4 (Active):
  0 12px 35px rgba(46, 125, 50, 0.2)

Special (Welcome):
  0 10px 30px rgba(46, 125, 50, 0.3)
```

---

## Typography Scale

```css
Display: 2.5rem (40px) - Welcome title
H1: 2.2rem (35.2px)
H2: 1.75rem (28px) - Section titles
H3: 1.4rem (22.4px) - Card titles
H4: 1.1rem (17.6px) - Subsections
Body: 1rem (16px) - Default text
Small: 0.9rem (14.4px) - Descriptions
Tiny: 0.8rem (12.8px) - Timestamps

Stat Number: 2.8rem (44.8px) - Extra large
```

**Font Weights:**
- Normal: 400
- Medium: 500
- Semibold: 600
- Bold: 700

---

## Icon Sizes

```css
Small: 24px × 24px (chart titles)
Medium: 32px × 32px (quick actions)
Large: 40px × 40px (stat cards)
XLarge: 70px × 70px (stat icons)
Illustration: 120px × 120px (welcome)
```

---

## Hover States

All interactive elements:
```css
Transition: all 0.3s ease
Transform: translateY(-4px) for elevation
Scale: 1.02 - 1.1 for emphasis
Border: Highlight with primary color
Shadow: Increase depth
```

---

## Responsive Breakpoints

```css
Mobile: max-width: 767px
Tablet: 768px - 1199px
Desktop: 1200px+

Container Max Width: 1600px
Padding Adjustment: 2rem → 1rem on mobile
```

---

## Accessibility

**Color Contrast:**
- Text on white: #1b5e20 (AAA compliant)
- White on primary: white on #2e7d32 (AAA)
- Secondary text: #558b2f (AA compliant)

**Interactive Sizes:**
- Minimum touch target: 44px × 44px
- Quick action cards: > 100px height
- Buttons: > 40px height

---

**Use this guide** to maintain consistency when adding new components or modifying existing styles.
