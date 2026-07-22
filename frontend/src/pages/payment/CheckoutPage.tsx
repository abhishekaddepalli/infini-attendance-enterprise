import React, { useState } from 'react';
import { motion } from 'framer-motion';
import { useNavigate } from 'react-router-dom';
import { CreditCard, Smartphone, Building2, Wallet, ShieldCheck, Check, ArrowRight, Gift, IndianRupee } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { toast } from 'sonner';
import { paymentApi } from '@/lib/api';

const plans = [
  { id: 'starter', name: 'Starter', monthlyPrice: 2999, yearlyPrice: 29990, maxEmployees: 50, popular: false },
  { id: 'professional', name: 'Professional', monthlyPrice: 7999, yearlyPrice: 79990, maxEmployees: 500, popular: true },
  { id: 'enterprise', name: 'Enterprise', monthlyPrice: 19999, yearlyPrice: 199990, maxEmployees: -1, popular: false },
];

export default function CheckoutPage() {
  const navigate = useNavigate();
  const [selectedPlan, setSelectedPlan] = useState('professional');
  const [billingCycle, setBillingCycle] = useState<'monthly' | 'yearly'>('monthly');
  const [couponCode, setCouponCode] = useState('');
  const [discount, setDiscount] = useState(0);
  const [isProcessing, setIsProcessing] = useState(false);

  const currentPlan = plans.find(p => p.id === selectedPlan)!;
  const basePrice = billingCycle === 'yearly' ? currentPlan.yearlyPrice : currentPlan.monthlyPrice;
  const total = Math.max(0, basePrice - discount);

  const handlePayment = async () => {
    setIsProcessing(true);
    try {
      const order = await paymentApi.createRazorpayOrder({ plan: selectedPlan, billing: billingCycle });
      const razorpay = new (window as any).Razorpay({
        key: order.key,
        amount: order.amount * 100,
        currency: 'INR',
        name: 'Infini Attendance',
        description: `${currentPlan.name} Plan`,
        order_id: order.order_id,
        handler: async (response: any) => {
          await paymentApi.verifyRazorpayPayment(response);
          toast.success('Payment successful!');
          navigate('/dashboard');
        },
        theme: { color: '#000080' },
      });
      razorpay.open();
    } catch (err: any) {
      toast.error(err.message || 'Payment failed');
    } finally {
      setIsProcessing(false);
    }
  };

  const handleApplyCoupon = async () => {
    try {
      const result = await paymentApi.applyCoupon(couponCode, selectedPlan, billingCycle);
      setDiscount(result.discount_amount);
      toast.success(`Coupon applied! Save ₹${result.discount_amount}`);
    } catch { toast.error('Invalid coupon code'); }
  };

  return (
    <div className="min-h-screen bg-slate-50">
      <header className="bg-white border-b sticky top-0 z-40">
        <div className="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 bg-navy-800 rounded-xl flex items-center justify-center">
              <span className="text-xl font-bold text-white">I</span>
            </div>
            <span className="text-xl font-bold text-navy-800">Infini<span className="text-saffron-500">.</span></span>
          </div>
          <div className="flex items-center gap-2 text-sm text-slate-500">
            <ShieldCheck className="h-4 w-4 text-green-500" /> 256-bit SSL Encrypted
          </div>
        </div>
      </header>

      <div className="max-w-6xl mx-auto px-6 py-12">
        <div className="text-center mb-10">
          <h1 className="text-3xl font-bold text-navy-800">Choose Your Plan</h1>
          <p className="mt-2 text-slate-500">Select the perfect plan for your organization</p>
        </div>

        <div className="flex justify-center mb-8">
          <div className="bg-slate-100 rounded-xl p-1 inline-flex">
            {(['monthly', 'yearly'] as const).map(cycle => (
              <button
                key={cycle}
                onClick={() => setBillingCycle(cycle)}
                className={`px-6 py-2.5 rounded-lg text-sm font-medium transition-all ${
                  billingCycle === cycle ? 'bg-white text-navy-800 shadow-sm' : 'text-slate-500'
                }`}
              >
                {cycle.charAt(0).toUpperCase() + cycle.slice(1)}
                {cycle === 'yearly' && <Badge className="ml-2 bg-green-100 text-green-700">Save 17%</Badge>}
              </button>
            ))}
          </div>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
          {plans.map(plan => {
            const price = billingCycle === 'yearly' ? plan.yearlyPrice : plan.monthlyPrice;
            return (
              <motion.div
                key={plan.id}
                whileHover={{ y: -4 }}
                onClick={() => setSelectedPlan(plan.id)}
                className={`cursor-pointer rounded-2xl border-2 p-6 transition-all ${
                  selectedPlan === plan.id
                    ? 'border-navy-800 bg-navy-50 ring-2 ring-navy-800 ring-offset-2'
                    : 'bg-white border-slate-200'
                }`}
              >
                {plan.popular && <Badge className="absolute -top-3 left-1/2 -translate-x-1/2 bg-navy-800">Most Popular</Badge>}
                <h3 className="text-lg font-bold text-navy-800">{plan.name}</h3>
                <div className="mt-4 mb-6">
                  <span className="text-3xl font-bold text-navy-800">₹{price.toLocaleString('en-IN')}</span>
                  <span className="text-slate-500">/mo</span>
                </div>
                <p className="text-sm text-slate-500 mb-6">
                  {plan.maxEmployees === -1 ? 'Unlimited' : `Up to ${plan.maxEmployees}`} employees
                </p>
                <Button variant={selectedPlan === plan.id ? 'premium' : 'outline'} className="w-full">
                  {selectedPlan === plan.id ? 'Selected' : 'Select'}
                </Button>
              </motion.div>
            );
          })}
        </div>

        <div className="max-w-md mx-auto bg-white rounded-2xl shadow-sm border p-6">
          <div className="mb-4 flex gap-2">
            <Input value={couponCode} onChange={e => setCouponCode(e.target.value.toUpperCase())} placeholder="Coupon code" />
            <Button variant="outline" onClick={handleApplyCoupon}><Gift className="h-4 w-4" /></Button>
          </div>
          {discount > 0 && <p className="text-sm text-green-600 mb-4">₹{discount} discount applied!</p>}
          <div className="flex justify-between font-bold text-navy-800 mb-6">
            <span>Total</span><span>₹{total.toLocaleString('en-IN')}</span>
          </div>
          <Button variant="premium" size="lg" className="w-full" onClick={handlePayment} loading={isProcessing}>
            <IndianRupee className="mr-2 h-5 w-5" /> Pay ₹{total.toLocaleString('en-IN')}
          </Button>
        </div>
      </div>
    </div>
  );
}
