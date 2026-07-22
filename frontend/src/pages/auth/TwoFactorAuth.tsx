import React from 'react';

export default function TwoFactorAuth() {
  return (
    <div className="flex min-h-screen items-center justify-center bg-slate-900 text-white">
      <div className="w-full max-w-md p-8 bg-slate-800 rounded-xl shadow-lg text-center">
        <h2 className="text-2xl font-bold mb-4">Two-Factor Authentication</h2>
        <p className="text-slate-400 mb-6">Enter the verification code sent to your device.</p>
        <input type="text" className="w-full p-3 bg-slate-900 border border-slate-700 rounded-lg text-center text-xl tracking-widest text-white mb-4" placeholder="000000" maxLength={6} />
        <button className="w-full py-3 bg-indigo-600 hover:bg-indigo-500 rounded-lg font-semibold transition">Verify Code</button>
      </div>
    </div>
  );
}
