const { chromium } = require(process.env.YASOFISH_PLAYWRIGHT || 'playwright');
const fs = require('fs');
(async () => {
  const browser = await chromium.launch({headless:true,channel:'msedge'});
  const page = await browser.newPage();
  const errors = [];
  page.on('pageerror', e => errors.push(e.message));
  const results = [];
  const base = process.env.YASOFISH_PREVIEW_URL || 'http://yasofish.local';
  for (const width of [1440,768,390]) {
    await page.setViewportSize({width,height:1000});
    const response = await page.goto(base+'/',{waitUntil:'networkidle'});
    results.push({width,status:response.status(),overflow:await page.evaluate(()=>document.documentElement.scrollWidth>innerWidth),hero:await page.locator('.yf-hero').count()});
    await page.screenshot({path:`outputs/home-${width}.png`,fullPage:true});
    if(width===1440 && process.env.YASOFISH_PREVIEW==='1') await page.screenshot({path:'yasofish-storefront-child/screenshot.png'});
  }
  for(const path of ['/shop/','/cart/','/checkout/','/my-account/']){
    const response=await page.goto(base+path,{waitUntil:'networkidle'});
    results.push({path,status:response.status(),title:await page.title()});
  }
  fs.writeFileSync('outputs/browser-results.json',JSON.stringify({results,errors},null,2));
  console.log(JSON.stringify({results,errors}));
  await browser.close();
})().catch(e=>{console.error(e);process.exit(1)});
